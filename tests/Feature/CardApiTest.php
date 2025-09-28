<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Validation\Rules\Can;

class CardApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    /** 一覧:200&配列で代える*/

    public function test_index_return_200_and_array(){

        //Cardのtitleを作成
        Card::create(['title' => 'A']);
        Card::create(['title' => 'B']);

        $res = $this->getJson('/api/v1/cards');

        $res->assertStatus(200)
            ->assertJsonIsArray()
            ->assertJsonCount(2); // 今回は paginate でなく配列返し
    }

    // public function test_store_returns_201_and_oersists(){
        
    //     $payload = ['title' => 'New card', 'description' => 'from test'];

    //     $res = $this->postJson('/api/v1/cards',$payload);

    //     $res->assertStatus(201)
    //         ->assertHeader('Location')
    //         ->assertJsonPath('data.title', 'New card');

    //     dump($res);
    // }

    public function test_store_returns_201_and_persists()
{
    $payload = ['title' => 'New card', 'description' => 'from test'];

    $res = $this->postJson('/api/v1/cards', $payload);

    $res->assertStatus(201)               // 201 のショートカット
        ->assertHeader('Location')      // Location が付いていること
        ->assertJsonPath('data.title', 'New card');

    // DBに本当に入ったか
    $id = $res->json('data.id');
    $this->assertDatabaseHas('cards', [
        'id'    => $id,
        'title' => 'New card',
        'description' => 'from test',
        // status をデフォルト open にしてるなら確認してもOK
        // 'status' => 'open',
    ]);
}

    public function test_show_return_404_when_not_found()
{
    $this->getJson('/api/v1/cards/99999')->assertStatus(404);
}

    public function test_update_return_200_and_updates_title(){

        $card = Card::create(['title'=>'old']);
        $res = $this->patchJson("/api/v1/cards/{$card->id}", ['title' => 'new']);

        $res->assertStatus(200)->assertJsonPath('data.title','new');

        $this->assertDatabaseHas('cards',['id'=>$card->id,'title'=>'new']);
    }


    /** 削除: 204（DBから消える） */
    public function test_destroy_returns_204_and_removes_row()
    {
        $card = Card::create(['title' => 'to delete']);

        $res = $this->deleteJson("/api/v1/cards/{$card->id}");

        $res->assertNoContent();
        $this->assertDatabaseMissing('cards', ['id' => $card->id]);
    }

    /** 作成: 422（title missing） */
    public function test_store_returns_422_when_title_missing()
    {
        $this->postJson('/api/v1/cards', ['description' => 'no title'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['title']);
    }

    /** 更新: 422（title empty はNG / missingはOKの対比を確認したい場合） */
    public function test_update_returns_422_when_title_empty()
    {
        $card = Card::create(['title' => 'keep']);

        $this->patchJson("/api/v1/cards/{$card->id}", ['title' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['title']);
    }

}
