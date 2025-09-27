<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    public function definition(): array
    {
        // 日本語ロケールの Faker を明示
        $f = \Faker\Factory::create('ja_JP');

        // ① 固定語彙から「業務っぽい」タイトルを合成
        $prefixes = ['要対応', '確認', '仕様検討', 'バグ修正', 'ドキュメント', 'レビュー', '設計', '調整'];
        $objects  = ['カードAPI', 'タイトル入力', '一覧ページ', 'バリデーション', 'Seeder', 'Factory', 'マイグレーション'];

        // ② 連番や日付を混ぜると“管理しやすい”
        static $seq = 1;
        $seqStr = str_pad((string)$seq++, 3, '0', STR_PAD_LEFT);

        // ③ タイトルは 25 文字以内に丸め（DB都合で）
        $title = sprintf('%s: %s #%s', 
            $prefixes[array_rand($prefixes)], 
            $objects[array_rand($objects)], 
            $seqStr
        );
        $title = mb_strimwidth($title, 0, 25, '');

        // ④ 説明は短文×2文くらい（読みやすさ重視）
        $desc = $f->realTextBetween(40, 80); // 日本語

        return [
            'title'       => $title,
            // 30%の確率で null、そうでなければ日本語説明
            'description' => $f->optional(0.3)->realTextBetween(40, 120) ?? null,
        ];
    }

    // 状態（State）例：必ず説明あり
    public function withDescription(): self
    {
        return $this->state(function (array $attrs) {
            $f = \Faker\Factory::create('ja_JP');
            return ['description' => $f->realTextBetween(60, 120)];
        });
    }
}
