# Laravel Action Tracker

This package provides an easy way to have a historical of actions done over models.

##Installation

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

##Usage

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
        'action2'
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

##Configuration

You can configure some options as model, table name and columns name prefix:

``` php
[

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
    'prefix' => 'custom_action_tracker'

]
```

##Troubleshooting

###Polymorphic relation

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