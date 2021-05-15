Quizoo platform
==============

This project is based on Sylius Admin platform. Project was done as bachelor thesis

Installation
------------

This project was made with docker images, Vagrant and VirtualBox. Follow the instructions to launch the system:

```bash
git clone git@github.com:andriusvo/quizoo-vms.git (Follow the instructions in VMS)
cd quizoo-vms
vagrant up
vagrant ssh
cd quizoo/projects/quizoo
docker-compose up
Open http://web.quizoo.test/ in your browser
```

That's it! You can go around and use quiz system.

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
