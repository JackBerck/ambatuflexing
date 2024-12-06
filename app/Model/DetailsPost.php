<?php

namespace JackBerck\Ambatuflexing\Model;

use JackBerck\Ambatuflexing\Domain\Post;

class DetailsPost
{
    public Post $post;
    public ?string $author = null;
    public ?string $authorPosition = null;
    public ?string $authorPhoto = null;
    public array $images = [];
}