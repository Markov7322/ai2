<?php

use App\Models\User;
use App\Models\Review;
use App\Models\ReviewObject;
use App\Models\Category;
use App\Models\Reaction;

it('allows user to like a review', function () {
    $user = User::factory()->create();
    $category = Category::create([
        'title' => 'Test',
        'slug' => 'test',
        'status' => 'active',
    ]);
    $object = ReviewObject::create([
        'category_id' => $category->id,
        'title' => 'Obj',
        'slug' => 'obj',
        'status' => 'approved',
    ]);
    $review = Review::create([
        'user_id' => $user->id,
        'review_object_id' => $object->id,
        'title' => 'Test',
        'content' => 'text',
        'status' => 'approved',
        'rating' => 5,
    ]);

    actingAs($user)
        ->post(route('reviews.react', $review), ['type' => 'like'])
        ->assertRedirect();

    expect(Reaction::where('user_id', $user->id)->where('review_id', $review->id)->where('type', 'like')->exists())->toBeTrue();
});
