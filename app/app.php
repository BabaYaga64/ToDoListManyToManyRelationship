<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    $app = new Silex\Application();

    $app['debug'] = true;

    $DB = new PDO('pgsql:host=localhost;dbname=to_do');

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

//#######################################Home Routes#######################################
//#######################################Home Routes#######################################

//#######################################Home Routes#######################################

    //Load homepage and display two links to categories and tasks
    $app->get("/", function() use ($app) {
        return $app['twig']->render('home.twig');

    });

//###################################Categories Routes#######################################
//###################################Categories Routes#######################################
//###################################Categories Routes#######################################
//###################################Categories Routes#######################################
    //Displays all the categories from the database when user clicks categories link
    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('DisplaysCategoriesAndForm.twig', array('category_array' => Category::getAll()));

    });


    //Posts new categories based on user input from form
    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('DisplaysCategoriesAndForm.twig', array('category_array' => Category::getAll()));

    });

    $app->post("/delete_categories", function() use ($app) {
        Category::deleteAll();
        return $app['twig']->render('DisplaysCategoriesAndForm.twig', array('category_array' => Category::getAll()));

    });


//#################################Tasks Routes########################################
//#################################Tasks  Routes####################################
//#################################Tasks Routes########################################
//###################################Tasks Routes########################################
//###################################Tasks Routes########################################
//###################################Tasks Routes########################################

    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('view_tasks.twig', array('tasks' => Task::getAll()));

    });

    //Posts new tasks based on user input from form
    $app->post("/tasks", function() use ($app) {
        $new_task = new Task($_POST['description']);
        $new_task->save();
        return $app['twig']->render('view_tasks.twig', array('tasks' => Task::getAll()));


    });


    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('view_tasks.twig', array('tasks' => Task::getAll()));

    });







    return $app;
?>
