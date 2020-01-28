# Laravel Action Tracker

This package provides an easy way to have an historical of actions done over models.

## Installation

Execute the following command to get the latest version of the package:

```
composer require devel8/laravel-action-tracker
```

Edit the config/app.php file and add the following line to register the service provider:

``` php
'providers' => [
    ...
    Devel8\LaravelActionTracker\ActionTrackerProvider::class,
],
```

Run publish command to copy package configuration:

```
php artisan vendor:publish --provider "Devel8\LaravelActionTracker\ActionTrackerProvider"
```

Finally, you will should run migration database command:

```
php artisan migrate
```

## Usage

Use `Devel8\LaravelActionTracker\ActionTrackerTrait` as trait in your model class:

``` php
class Post extends Model
{
    use ActionTrackerTrait;
```

Specify the actions done in the model, defining an `actions` attribute in your model class:

``` php
protected $actions = [
        'closed',
        'created'
    ];
```

Register an action done over a model executing `doActionTracker` method as below:

``` php
$user = Auth::user();
$post = Post::find(56);
$post->doActionTracker('closed', "Post was closed by {$user->user_nick}");
```

Get the actions over a specific model:

``` php
// Retrieve all actions over a specific model
$post = Post::find(56);
$actions = $post->actionTracker()->get();

// Retrieve one action over a specific model:
$post = Post::find(56);
$actions = $post->actionTracker()->where('action', 'closed')->get();
```

## Events

The Eloquent model method `doActionTracker` dispatch a generic event in each action tracker registration.
You can listen this event adding the below code in your `App\Providers\EventServiceProvider`:
``` php
/**
 * The event listener mappings for the application.
 *
 * @var array
 */
protected $listen = [
    \Devel8\LaravelActionTracker\ActionTracked::class => [
        \App\Listeners\YourListener::class,
    ],
];
```

Whether you prefer listen an action, you can add your custom event for each action adding it at the property `actionEvents` in your Eloquent model as below:
``` php
    /**
     * Action events list
     */
    protected array $actionEvents = [
        'closed' => \App\Events\PostClosed::class
    ];
``` 

ActionTracker send the Eloquent model `ActionTracker` as argument to the constructor of your custom action event.
Therefore the event class looks like:
``` php
    class PostClosed
    {
    
        use SerializesModels;
    
        public ActionTracker $actionTracker;
    
        /**
         * ActionTracked constructor.
         *
         * @param ActionTracker $actionTracker
         */
        public function __construct(ActionTracker $actionTracker)
        {
            $this->actionTracker = $actionTracker;
        }
    
    }
``` 

You can follow [the official laravel documentation](https://laravel.com/docs) for further information about [register events and listeners](https://laravel.com/docs/5.8/events).


## Configuration

You can configure some options as model, table name and columns name prefix:

``` php
[

    /*
     * Disable all action tracking.
     * Therefore if disabled the actions will not be persisted in the database.
     */
    'disable' => false,

    /*
     * Action Tracker Model Class
     */
    'model' => \CustomActionTracker::class,

    /*
     * Database table where actions are tracked
     */
    'table_name' => 'custom_action_trackers',

    /*
     * Prefix word used to database columns name
     */
    'prefix' => 'custom_action_tracker',
                                       
    /*
    * Log action tracking in the log as info type.
    */
    'log_tracking' => false

]
```

## Troubleshooting

### Polymorphic relation

This package uses polymorphic relationship, so you can get troubles when you want to retrieve actions information by models.
By default Laravel saves model namespace in the database for relation model purpose. As namespaces have backslashes it could be a trouble when you filter by model.
To avoid this you can map model class names in your `AppService Provider`:
``` php
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'Post' => \Entities\Post::class
        ]);
    }
```