<?php

namespace Tests\Feature;

use App\Classes\LaravelGraphQLTest\TestGraphQL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CalculationTest extends TestCase
{
    use RefreshDatabase,
        DatabaseMigrations,
        TestGraphQL;

    /**
     * Calculate price for visitor without discounts.
     *
     * @return void
     */
    public function test_visitor_without_discount()
    {
        $cpp = 3;
        $pages = 12;
        $this->query('calculation', [
            'type' => 1, 'pages' => 12
        ], [
            'total', 'level', 'hours', 'pages', 'deadline','cpp',
            'discounts' => ['id', 'name']
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['level' => 1])
            ->assertJsonFragment(['pages' => $pages])
            ->assertJsonFragment(['cpp' => $cpp])
            ->assertJsonFragment(['total' => $this->total($cpp * $pages)])
            ->assertJsonFragment(['hours' => 240])
            ->assertJsonFragment(['discounts' => []]);
    }

    /**
     * Calculate price for visitor with pages discounts.
     *
     * @return void
     */
    public function test_visitor_discount_pages()
    {
        $cpp = 3;
        $data = [
            /*5% discount on pages 20-49 to visitor*/
            ['pages' => 20, 'dname' => 'for 20-49 pages', 'dvalue' => 5, 'dvalue_type' => 1],
            ['pages' => 49, 'dname' => 'for 20-49 pages', 'dvalue' => 5, 'dvalue_type' => 1],
            /*10% discount on pages 50-99 to visitor*/
            ['pages' => 50, 'dname' => 'for 50-99 pages', 'dvalue' => 10, 'dvalue_type' => 1],
            ['pages' => 99,  'dname' => 'for 50-99 pages', 'dvalue' => 10, 'dvalue_type' => 1],
            /*15% discount on pages 20 to visitor*/
            ['pages' => 100, 'dname' => 'for 100+ pages', 'dvalue' => 15, 'dvalue_type' => 1],
            ['pages' => 200, 'dname' => 'for 100+ pages', 'dvalue' => 15, 'dvalue_type' => 1],
        ];
        foreach ($data as $items) {
            $this->query('calculation', [
                'type' => 1,
                'pages' => $items['pages']
            ], [
                'total', 'level', 'hours', 'pages', 'deadline', 'cpp',
                'discounts' => ['name', 'value', 'value_type']
            ])
                ->assertSuccessful()
                ->assertJsonFragment(['level' => 1])
                ->assertJsonFragment(['pages' => $items['pages']])
                ->assertJsonFragment(['cpp' => $cpp])
                ->assertJsonFragment(['total' => $this->total($cpp * $items['pages'], $items['dvalue'], $items['dvalue_type'])])
                ->assertJsonFragment(['hours' => 240])
                ->assertJsonFragment([
                    'discounts' => [
                        [
                            'name' => $items['dname'], 'value' => $items['dvalue'], 'value_type' => $items['dvalue_type']
                        ]
                    ]
                ]);
        }
    }

    /**
     * Calculate price for visitor with pages discounts.
     *
     * @return void
     */
    public function test_visitor_discount_first_promo()
    {
        $cpp = 3;
        $data = [
            /*promo discount*/
            ['promo' => 'FIRST10', 'pages' => 20, 'dpromo' => 'FIRST10', 'dname' => null, 'dvalue' => 10, 'dvalue_type' => 1],
            /*15% discount on pages for 100+ pages*/
            ['promo' => 'FIRST10', 'pages' => 100, 'dpromo'=> null, 'dname' => 'for 100+ pages', 'dvalue' => 15, 'dvalue_type' => 1],
        ];
        foreach ($data as $items) {
            $this->query('calculation', [
                'type' => 1,
                'pages' => $items['pages'],
                'promo' => $items['promo']
            ], [
                'total', 'level', 'hours', 'pages', 'deadline', 'cpp',
                'discounts' => ['promo', 'name', 'value', 'value_type']
            ])
                ->assertSuccessful()
                ->assertJsonFragment(['level' => 1])
                ->assertJsonFragment(['pages' => $items['pages']])
                ->assertJsonFragment(['cpp' => $cpp])
                ->assertJsonFragment(['total' => $this->total($cpp * $items['pages'], $items['dvalue'], $items['dvalue_type'])])
                ->assertJsonFragment(['hours' => 240])
                ->assertJsonFragment(['discounts' => [
                    [
                        'promo' => $items['dpromo'],
                        'name' => $items['dname'],
                        'value' => $items['dvalue'],
                        'value_type' => $items['dvalue_type']
                    ]
                ]]);
        }
    }

    /**
     * Calculate for customer without discounts.
     *
     * @throws \Exception
     */
    public function test_new_customer_discount()
    {
        $cpp = 3;
        $pages = 12;
        $this->customerLogin('customer2@test.com');
        $data = [
            /*without discount*/
            ['pages' => 5],
            /*Promo discount*/
            ['promo' => 'FIRST10', 'pages' => 20, 'dpromo' => 'FIRST10', 'dname' => null, 'dvalue' => 10, 'dvalue_type' => 1],
            /*5% discount on pages 20-49 to visitor*/
            ['pages' => 20, 'dname' => 'for 20-49 pages', 'dvalue' => 5, 'dvalue_type' => 1],
            ['pages' => 49, 'dname' => 'for 20-49 pages', 'dvalue' => 5, 'dvalue_type' => 1],
            /*10% discount on pages 50-99 to visitor*/
            ['pages' => 50, 'dname' => 'for 50-99 pages', 'dvalue' => 10, 'dvalue_type' => 1],
            ['pages' => 99,  'dname' => 'for 50-99 pages', 'dvalue' => 10, 'dvalue_type' => 1],
            /*15% discount on pages 20 to visitor*/
            ['pages' => 100, 'dname' => 'for 100+ pages', 'dvalue' => 15, 'dvalue_type' => 1],
            ['pages' => 200, 'dname' => 'for 100+ pages', 'dvalue' => 15, 'dvalue_type' => 1],
        ];
        foreach ($data as $item) {
            $discounts = !isset($item['dname']) ? [] : [
                'promo' => $item['dpromo'],
                'name' => $item['dname'],
                'value' => $item['dvalue'],
                'value_type' => $item['dvalue_type']
            ];
            $this->query('calculation', [
                'type' => 1,
                'pages' => $item['pages']
            ], [
                'total',
                'level',
                'hours',
                'pages',
                'deadline',
                'cpp',
                'discounts' => ['id', 'name']
            ])
                ->assertSuccessful()
                ->assertJsonFragment(['level' => 1])
                ->assertJsonFragment(['pages' => $item['pages']])
                ->assertJsonFragment(['cpp' => $cpp])
                ->assertJsonFragment(['total' => $this->total($cpp * $item['pages'], $item['dvalue'], $item['dvalue_type'])])
                ->assertJsonFragment(['hours' => 240])
                ->assertJsonFragment(['discounts' => $discounts]);
        }
    }

    /**
     * Calculate for customer with personal auto discounts.
     *
     * @throws \Exception
     */
    public function test_customer_personal_discount()
    {
        $cpp = 3;
        $pages = 12;
        $data = [
            ['dname' => 'Client 3 discount', 'dvalue' => 4, 'dvalue_type' => 1],
        ];

        $this->customerLogin();
        foreach ($data as $items) {
            $this->query('calculation', [
                'type' => 1,
                'pages' => $pages
            ], [
                'total', 'level','hours', 'pages', 'deadline', 'cpp',
                'discounts' => ['name', 'value', 'value_type']
            ])
                ->assertSuccessful()
                ->assertJsonFragment(['level' => 1])
                ->assertJsonFragment(['pages' => $pages])
                ->assertJsonFragment(['cpp' => $cpp])
                ->assertJsonFragment(['total' => $this->total($cpp * $pages, $items['dvalue'], $items['dvalue_type'])])
                ->assertJsonFragment(['hours' => 240])
                ->assertJsonFragment([
                    'discounts' => [
                        [
                            'name' => $items['dname'],
                            'value' => $items['dvalue'],
                            'value_type' => $items['dvalue_type']
                        ]
                    ]
                ]);
        }
    }

    /**
     * @param $cpp
     * @param $discount_value
     * @param $discount_value_type
     * @return float|int
     */
    protected function total($total, $discount_value = null, $discount_value_type = null)
    {
        if ($discount_value_type == config('discount.types.percent')) {
            return round($total * (1- ($discount_value / 100)), 2);
        } elseif ($discount_value_type == config('discount.types.value')) {
            return $total - $discount_value;
        } else {
            return $total;
        }
    }

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->artisan('db:seed');
        // alternatively you can call
        // $this->seed();
    }
}
