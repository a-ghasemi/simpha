# A MVC framework over PHP

## Getting Started
1. run `composer install` in root folder
2. create your routes
3. create your views
4. enjoy

## Documentation
### Routing
route format is: example.com/class/method/slashed_params

this means: if you want above route, follow these steps:
1. create ClassController.php in app/controllers
2. create `pubic function method()` in it
3. specify request type, `post_method()`, just gets POST request
* your options are HTTP request types: `get_`,`post_`,`put_`,`patch_`,`delete_`,...
* if you are going to accept all kind of methods, use `any_`
* no route.php file, because this is **SIMPLE MVC**

## Views
if you want to return a view as a result of a route in corresponded function (that described in Routing section), do this:
> return View::show('home.index')

this means, send <root>/views/home/index.blade.php to client
* You have powerful **blade engine** here, many thanks to @jenssegers
* Do not echo anything, because it is MVC

## Models
my next TODO ...

##.env
You have .env file here. and an ENV_Parser helps to parse it.

Read env flags like this: (*I promise make this easier in the feature*)
> \App\Kernel::$env['flag_label']

## Contribution
If you want to participate in this repo, you always welcomed.
Parts to contribution:
* Adding New Features
* Testing
* Performance Improvement
* Clean Code advices
