<?php
use App\Models\{User,Category,Review,Comment};

it('user can reply to comment', function () {
    $user = User::factory()->create();
    $category = Category::create(['title' => 'Test','slug' => 'test','status'=>'active']);
    $review = Review::create([
        'user_id' => $user->id,
        'category_id' => $category->id,
        'content' => 'text',
        'status' => 'approved',
        'rating' => 5,
    ]);
    $comment = Comment::create(['user_id'=>$user->id,'review_id'=>$review->id,'content'=>'one']);

    actingAs($user)
        ->post(route('reviews.comments.store',$review),[
            'content' => 'reply',
            'parent_id' => $comment->id,
        ])->assertRedirect();

    expect(Comment::where('parent_id',$comment->id)->where('content','reply')->exists())->toBeTrue();
});
