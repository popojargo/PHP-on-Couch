Contributing
============

Feel free to make any contributions. All contributions must follow the Coding Style Guide and must also comes with valid and complete tests.


Generating the documentation
----------------------------

To build the documentation, simply run the following script :

.. code-block:: bash

    doc/make html

Running the tests
-----------------

For development purpose, we usually run the tests on a docker image. We have some predefined scripts in the *bin* folder.

You usually run either :

- _runLocalWin.sh (For Windows)
- _runLocalUnix.sh (For OSX and Linux)

Parameters available:

- DB_PORT(5984): The port of the database
- DB_HOST(localhost): The host of the database
- NAME(phponcouch_test_db): The name of the docker image.

Those scripts stop and delete the docker box. Then, it runs *_resetDB.sh*.

The following script does the following :

- Installs latest composer packages
- Starts the docker image
- Creates the databases required
- Seeds the database and setup users
- Runs the tests
- Validates the codestyle



