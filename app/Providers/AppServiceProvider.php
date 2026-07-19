<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Model::preventLazyLoading(!app()->isProduction());

        View::share('user', (object)[
            'name' => 'Web Pro',
            'isAdmin' => true,
            'isSubscribed' => false,
        ]);
        View::composer('components.notification-sidebar', function($view) {
            //temporary: hardcoded collection so the component works now
            //replace this in module 2 with a real eloquent query
            /*$notifications = collect([
                (object)['title'=>'New comment on your post', 'body'=> 'John replied to your post.'],
                (object)['title'=>'Your post was liked', 'body'=> 'Sara liked your post..'],
                (object)['title'=>'Welcome to the platform', 'body'=> 'Thanks for joining us!'],
            ]);*/
         
            $notifications = collect([
                (object)[
                    'type'=>'comment',
                    'title' => 'John replied to your post',
                    'body'=> 'Great article! Really helped me understand closures.',
                    'time'=>'2 min ago',
                    'read'=>false
                ],
                (object)[
                    'type'=>'like',
                    'title' => 'Sara liked your post',
                    'body'=> null,
                    'time'=>'10 min ago',
                    'read'=>false
                ],
                (object)[
                    'type'=>'follow',
                    'title' => 'Alex started following you',
                    'body'=> null,
                    'time'=>'1 hour ago',
                    'read'=>true
                ],
                (object)[
                    'type'=>'system',
                    'title' => 'Your account was verified',
                    'body'=> 'You can now publish posts.',
                    'time'=>'1 hour ago',
                    'read'=>true
                ],
                (object)[
                    'type'=>'unknown',
                    'title' => 'Something happened',
                    'body'=> 'We are not sure what.',
                    'time'=>'yesterday',
                    'read'=>false
                ],

            ]);
            // Now — real database query
            /*$notifications = auth()->check()
            ? auth()->user()
                    ->notifications()
                    ->latest()
                    ->take(10)
                    ->get()
            : collect(); // empty collection for guests*/

            $view->with('notifications', $notifications);
        });
    }
}
