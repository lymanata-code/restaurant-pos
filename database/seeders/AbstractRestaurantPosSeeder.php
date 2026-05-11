<?php

namespace Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

abstract class AbstractRestaurantPosSeeder extends Seeder
{
    protected CarbonImmutable $seedNow;

    final public function run(): void
    {
        $this->seedNow = CarbonImmutable::parse('2026-05-11 11:30:00', config('app.timezone'));
        $this->seed();
    }

    abstract protected function seed(): void;

    protected function now(): CarbonImmutable
    {
        return $this->seedNow;
    }

    protected function json(array $payload): string
    {
        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function find(string $table, array $attributes): object
    {
        $row = $this->matchingQuery($table, $attributes)->first();

        if (! $row) {
            throw new RuntimeException("Seed row not found in {$table}.");
        }

        return $row;
    }

    protected function row(string $table, array $attributes, array $values = []): object
    {
        $existing = $this->matchingQuery($table, $attributes)->first();

        if ($existing) {
            DB::table($table)
                ->where('id', $existing->id)
                ->update(array_merge($values, ['updated_at' => $this->now()]));

            return $this->find($table, ['id' => $existing->id]);
        }

        $id = DB::table($table)->insertGetId(array_merge(
            $attributes,
            $values,
            ['created_at' => $this->now(), 'updated_at' => $this->now()],
        ));

        return $this->find($table, ['id' => $id]);
    }

    protected function branch(string $code): object
    {
        return $this->find('restaurants', ['code' => $code]);
    }

    private function matchingQuery(string $table, array $attributes)
    {
        $query = DB::table($table);

        foreach ($attributes as $column => $value) {
            if ($value === null) {
                $query->whereNull($column);
            } else {
                $query->where($column, $value);
            }
        }

        return $query;
    }
}
