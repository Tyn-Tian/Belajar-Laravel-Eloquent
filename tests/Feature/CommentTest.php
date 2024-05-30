<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateComment()
    {
        $comment = new Comment();
        $comment->email = "test@gmail.com";
        $comment->title = "Test Title";
        $comment->comment = "Test Commnet";
        $comment->commentable_id = '1';
        $comment->commentable_type = 'product';
        $comment->save();

        self::assertNotNull($comment->id);
    }

    public function testDefaultAttributeValues()
    {
        $comment = new Comment();
        $comment->email = "test@gmail.com";
        $comment->commentable_id = '1';
        $comment->commentable_type = 'product';
        $comment->save();

        self::assertNotNull($comment->id);
        self::assertNotNull($comment->title);
        self::assertNotNull($comment->comment);
    }
}
