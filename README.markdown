StnwAvatarBundle
=====================
Simple Symfony2 bundle to generate avatars. Provides specification of male & female avatar's version, size, possibility to save or to output in a browser. Already includes templates of avatars. Also you can add/change your templates.

Installation
------------

  1. Add this bundle to your debs file.

          [StnwAvatarBundle]
             git=https://github.com/stnw/StnwAvatarBundle
             target=/bundles/Stnw/AvatarBundle

  2. Add the following dependency to your project’s composer.json file:

          "require": {
            // ...
            "stnw/avatar-bundle": "dev-master"
            // ...
          }

 or `Stnw` namespace to your autoloader:

          // app/autoload.php
          $loader->registerNamespaces(array(
             'Stnw' => __DIR__ . '/../vendor/bundles',
             // your other namespaces
          );


  3. Add this bundle to your application kernel:

          // app/AppKernel.php
          public function registerBundles()
          {
              return array(
                  // ...
                  new Stnw\AvatarBundle\AvatarBundle(),
                  // ...
              );
          }
  4. Update vendors:

          php bin/vendors update

  5. If you want to change default avatar templates, configure the `stnw_avatar` service in your config:

          # application/config/config.yml
          stnw_avatar:
            avatar.folder:  #path to the folder with male & female templates folders
            avatar.male_folders: [face, hair, nose, eye, mouth] #folders with templates. From every folder one png file will be  a layer for avatar.
            avatar.female_folders: [hair, face, nose, eye, mouth]

Usage
-----

To generate an avatar in a folder:

          $avatarManager = $this->get('avatar.manager');
          $avatarPach = $this->get('kernel')->getRootDir() . '/../web/avatar.png';
          $result = $avatarManager->generateAvatar($avatarPach);

Or with parameters:

          $result = $avatarManager->generateAvatar($avatarPath, 'female', 100);

The only required parameter is the path for the new avatar. The rest have default values.
