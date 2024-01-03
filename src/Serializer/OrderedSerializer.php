<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Serializer;

use OpenSearchDSL\SearchEndpoint\AbstractSearchEndpoint;

class OrderedSerializer
{
    public function normalize($data)
    {
        $data = is_array($data) ? $this->order($data) : $data;

        if (is_iterable($data)) {
            foreach ($data as $key => $value) {
                if ($value instanceof AbstractSearchEndpoint) {
                    $normalize = $value->normalize();

                    if ($normalize === null || count($normalize) === 0) {
                        unset($data[$key]);

                        continue;
                    }

                    $data[$key] = $normalize;
                }
            }
        }

        return $data;
    }

    private function order(array $data): array
    {
        $filteredData = array_filter(
            $data,
            static fn ($value) => $value instanceof AbstractSearchEndpoint
        );

        uasort(
            $filteredData,
            static fn (AbstractSearchEndpoint $a, AbstractSearchEndpoint $b) => $a->getOrder() <=> $b->getOrder()
        );

        return array_merge($filteredData, array_diff_key($data, $filteredData));
    }
}
