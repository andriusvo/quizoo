Quizoo platform
==============

This project is based on Sylius Admin platform. Project was done as bachelor thesis

Installation
------------

This project was made with docker images and docker-compose.You can find `docker-compose.yaml` in root directory.\
Before booting up the project, verify that you have `docker-compose` installed: 

`docker-compose -v`

After you verify your `docker-compose` version, input the following:

```bash
git clone git@github.com:andriusvo/quizoo.git
cd quizoo
docker-compose up -d
open http://web.quizoo.test/
```

And that's it! You can go around and use quiz system.

Credentials
-------------------
There are 3 existing users created as a fixtures:

```bash
ADMIN: username - admin, password: admin
ROLE_TEACHER: username: teacher-1, password: teacher
ROLE_STUDENT: username: student-1, password: student
```

Running behat tests
-------------------

If you want to run Behat, use this command:

```bash
$ ./bin/behat
```

If you want to run Behat with local chrome, follow this steps:

1. Chromedriver should be installed locally
2. Run ``chromedriver --url-base=wd/hub --port=4444 --whitelisted-ips=your-vm-ip``
3. Create ``behat.yaml`` in ``root`` directory.
4. Copy-paste the following code snippet:
```bash
imports:
    - tests/Behat/Resources/config/suites.yaml

default:
    extensions:
        Behat\MinkExtension:
            base_url: http://web.quizoo.test:8000/
            browser_name: chrome
            default_session: chrome
            javascript_session: chrome
            sessions:
                chrome:
                    selenium2:
                        wd_host: "http://your-local-ip:4444/wd/hub"
                        capabilities:
                            chrome:
                                switches:
                                    - "--headless"
                                    - "--disable-gpu"
                                    - "--no-sandbox"
        FriendsOfBehat\SymfonyExtension:
            bootstrap: tests/bootstrap.php
            kernel:
                path: src/Kernel.php
                class: App\Kernel
                environment: test
                debug: false
```
