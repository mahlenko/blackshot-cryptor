<?php


namespace App\Helpers;


class Data
{
    /**
     * Сгенерирует варианты из массива
     * @param $options
     * @return \Illuminate\Support\Collection
     */
    public static function generationVariants($options): \Illuminate\Support\Collection
    {
        $combinations = [[]];

        for ($count = 0; $count < $options->count(); $count++) {
            $tmp = [];
            foreach ($combinations as $v1) {
                foreach ($options[$count] as $v2) {
                    $tmp[] = array_merge($v1, [$v2]);
                }
            }

            $combinations = $tmp;
        }

        $combinations = array_filter($combinations);

        foreach ($combinations as $index => $combination) {
            $combinations[$index] = collect($combination);
        }

        return collect($combinations);
    }
}
