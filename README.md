# A MVC framework over PHP
Under development ...

## Documentation
### Routing
route format is: example.com/class/method/slashed_params

this means: if you want above route, follow these steps:
1. create ClassController.php in app/controllers
2. create `pubic function method()` in it
3. specify request type, `post_method()`, just gets POST request
* your options are HTTP request types: `get_`,`post_`,`put_`,`patch_`,`delete_`,...
* if you are going to accept all kind of methods, use `any_`

## Contribution
If you want to participate in this repo, you always welcomed.
Parts to contribution:
* Adding New Features
* Testing
* Performance Improvement
* Clean Code advices
