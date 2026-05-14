<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlogPostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_blog_posts');
    }

    public function view(User $user, BlogPost $blogPost): bool
    {
        return $user->can('view_blog_posts');
    }

    public function create(User $user): bool
    {
        return $user->can('create_blog_posts');
    }

    public function update(User $user, BlogPost $blogPost): bool
    {
        // L'autore può sempre modificare i propri post
        if ($blogPost->author_user_id === $user->id) {
            return true;
        }
        
        return $user->can('update_blog_posts');
    }

    public function delete(User $user, BlogPost $blogPost): bool
    {
        // L'autore può sempre eliminare i propri post
        if ($blogPost->author_user_id === $user->id) {
            return true;
        }
        
        return $user->can('delete_blog_posts');
    }

    public function restore(User $user, BlogPost $blogPost): bool
    {
        return $user->can('restore_blog_posts');
    }

    public function forceDelete(User $user, BlogPost $blogPost): bool
    {
        return $user->can('force_delete_blog_posts');
    }
}