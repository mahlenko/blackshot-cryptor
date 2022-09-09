<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property Model $model
 * @package App\Helpers
 */
class Nested
{
    /**
     * @var string
     */
    private $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $model
     * @return self
     */
    public static function model(string $model): self
    {
        return new self($model);
    }

    /**
     * @param string $locale
     * @param string|null $ignore_uuid
     * @param array $where
     * @return array
     */
    public function optGroup(string $locale, string $ignore_uuid = null, array $where = []): array
    {
        $nodes = $this->model::where($where)
            ->withTranslation()
            ->with('ancestors')
//            ->translatedIn($locale)
            ->defaultOrder()
            ->get()
            ->toTree();

        $groups = [];

        if ($nodes) {
            $traverse = function ($nodes, bool $disabled = false, $prefix = '-', $prefix_char = '-') use (&$traverse, &$groups, $ignore_uuid) {
                foreach ($nodes as $node) {

                    $groups[] = [
                        'value' => $node->uuid,
                        'text'  => $prefix .' '. $node->name,
                        'disabled' => $disabled ?: $ignore_uuid === $node->uuid,
                        'name' => $node->name,
                        'level' => strlen($prefix),
                        'level0' => strlen($prefix) - 1,
//                        'parent' => $ancestors_count ? $node->ancestors : false,
//                        'parent_uuids' => $ancestors_count ? $node->ancestors->pluck('uuid')->toArray() : [],
//                        'children' => $descendants_count ? $node->descendants : false,
//                        'children_uuids' => $descendants_count ? $node->descendants->pluck('uuid')->toArray() : [],
                        'eloquent' => $node
                    ];

                    if ($node->children->count()) {
                        if ($disabled === false && $ignore_uuid === $node->uuid) {
                            $disabled = true;
                        }
                        $traverse($node->children, $disabled, $prefix . $prefix_char);
                    }
                }
            };

            $traverse($nodes);
        }

        return $groups;
    }

    /**
     * @param Model $node
     * @param $position
     * @param $parent_id
     */
    public function tree(Model &$node, $position, $parent_id): void
    {
        /**
         * Ничего не делаем если ничего не изменилось.
         * $position -> append (по-умолчанию в интерфейсе)
         */
        if ($position === 'append' && isset($node)) {
            if ($node->parent_id == $parent_id) {
                return;
            }
        }

        /* @var $node NodeTrait */
        if (!empty($parent_id)) {
            $neighbor = $this->model::where('uuid', $parent_id)->first();
            switch ($position) {
                case 'before': $node->beforeNode($neighbor); break;
                case 'after': $node->afterNode($neighbor); break;
                case 'root': $node->makeRoot(); break;
                default: $node->appendToNode($neighbor); break;
            }
            return;
        }

        $node->makeRoot();
    }

    /**
     * Сортирует элемент выше или ниже.
     * @param string $uuid
     * @param int $amount На сколько пунктов поднять или опустить элемент
     * @param array $scope
     * @return bool
     */
    public function sortable(string $uuid, int $amount, array $scope = []): bool
    {
        /* @var NodeTrait $node */
//        $node = $this->model::where(array_merge(['uuid' => $uuid], $where))->first();
        $node = $this->model::where(['uuid' => $uuid])->first();

        return $amount > 0
            ? $node->up($amount)
            : $node->down(abs($amount));
    }
}
