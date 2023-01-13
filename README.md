# Technical Documentation

#### 1. The problem and bad practices.

The `old code` is a good code as it also uses OOP, but it does now use the fully potential.
First, I've analyzed that the problem lies in the structure, basically, if a developer wants to add
a new database table e.g. `users` table, the developer also want to add a new `Manager` class e.g. `UserManager`
for that table. Therefore, the developer also need to add methods corresponding to that manager class, all the HTTP
request or
queries needed. So technically, the developer always repeats the code or retypes every same concept in each manager
class,
and therefore, not easy to maintain or scale. Second problem, the code does not properly use the polymorphic method, so
there are no `inheritance` or `extended classes` to another classes therefore not fully **reusable**.

#### 2. Explain or discuss the changes or improvements you’ve made in the codes.

We can find the rewritten code and the full-implementation in the `database` folder.
First, as you can see I have structured the folder in a manner in which it is easier to locate and organise files.
The folder structure explanation below:

    .
    ├── ...
    ├── database            # Main exam folder
    │ ├── Http              # The connection and query classes
    │ ├── Model             # The model or entity class, we can add more classes base on database table.
    │ ├── autoload.php      # Autoloader class
    │ ├── config.ini        # Configuration file
    │ └── index.php         # Test script
    └── ...

The pattern that I've used is easier to maintain and reuse because it uses proper
OOP and inheritance rules. The core lies in the `Http` folder, the `Connection::class`
holds the communication to the database. The `Model::class` is used as the parent model to
those entities e.g. News and Comments, in which are inherited by the two. The `Model::class`
basically sets up the properties of entities like `table` or `relations`. The `Query::class` is where the
SQL queries are executed, this class holds the different parameters like the fields we want to fetch and the table that
is targeted. In this exam I've only added the `READ` query such as SQL `select`, of course in this new code,
we can easily scale and add queries like `updates`, `delete`, and `create`.

The `Model` folder has all the entities to corresponding tables, we can add as many as we want as long as we inherit the
`Model::class` in the `Http` folder.

To further check, there are code comments included in the classes and methods.

To test the code, simple run the command in the console:

`$ php database/index.php`

This will print...

>this is the description of our fist news<br>
this is the description of our second news<br>
this is the description of our third news<br>
this is the description of our fist news<br>
i have no opinion about that<br>
news 2<br>

You can track the commit here: https://github.com/isaacdarcilla/eastvantage-phptest/commit/3f334a3d597003e0bfd318d0de5b918db6a5030f

-- [Isaac Arcilla](https://isaacdarcilla.framer.website)