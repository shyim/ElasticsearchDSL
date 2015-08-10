<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\ElasticsearchDSL\Tests\Unit\DSL\Aggregation;

use ONGR\ElasticsearchDSL\Aggregation\TopHitsAggregation;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use ONGR\ElasticsearchDSL\Sort\Sorts;

/**
 * Unit tests for top hits aggregation.
 */
class TopHitsAggregationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Check if aggregation returns the expected array.
     */
    public function testToArray()
    {
        $sort = new FieldSort('acme');
        $aggregation = new TopHitsAggregation('acme', 1, 1, $sort);

        $expected = [
            'acme' => [
                'top_hits' => [
                    'sort' => [
                        'acme' => [],
                    ],
                    'size' => 1,
                    'from' => 1,
                ],
            ],
        ];

        $this->assertSame($expected, $aggregation->toArray());
    }

    /**
     * Check if parameters can be set to agg.
     */
    public function testParametersAddition()
    {
        $aggregation = new TopHitsAggregation('acme', 0, 1);
        $aggregation->addParameter('_source', ['include' => ['title']]);

        $expected = [
            'acme' => [
                'top_hits' => [
                    'size' => 0,
                    'from' => 1,
                    '_source' => [
                        'include' => ['title'],
                    ]
                ],
            ],
        ];

        $this->assertSame($expected, $aggregation->toArray());
    }
}
