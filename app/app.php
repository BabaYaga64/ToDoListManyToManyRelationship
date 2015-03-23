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


    //Posts new categories based on user input from DisplaysCategoriesAndForm.twig and displays on same page.
    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('DisplaysCategoriesAndForm.twig', array('category_array' => Category::getAll()));

    });


    //Delete all categories and displays results on DisplaysCategoriesAndForm.twig page
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

    //Takes input from DisplaysCategoriesAndForm.twig and gets all tasks from the DB. Displays results on the view_tasks.twig page.
    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('view_tasks.twig', array('tasks' => Task::getAll()));

    });

    //Posts new tasks based on user input from DisplaysCategoriesAndForm.twig form and displays results on view_tasls.twig page.
    $app->post("/tasks", function() use ($app) {
        $new_task = new Task($_POST['description']);
        $new_task->save();
        return $app['twig']->render('view_tasks.twig', array('tasks' => Task::getAll()));


    });

    //Pulls tasks by id from view_tasks.twig page and displays it on task_and_its_categories.twig page. We call the getCategories method so that users can add which category a task belongs to.
    $app->get("/tasks/{id}", function($id) use ($app) {
    $find_task = Task::find($id);
        return $app['twig']->render('task_and_its_categories.twig', array('task' => $find_task, 'categories' => $find_task->getCategories(), 'all_categories' => Category::getAll()));
    });


    //Adding a category to a task. Starting from the /tasks/{id} route, look for category_id and task_id from the form. Both of these are stored in $_POST array. 
    $app->post("/add_categories", function() use ($app) {
        $category = Category::find($_POST['category_id']);
        $task = Task::find($_POST['task_id']);
        $task->addCategory($category);
        return $app['twig']->render('task_and_its_categories.twig', array('task' => $task, 'tasks' => Task::getAll(), 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });

    //Deletes all tasks.
    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('view_tasks.twig', array('tasks' => Task::getAll()));

    });







    return $app;
?>
