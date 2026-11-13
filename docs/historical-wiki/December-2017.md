[TOC]

# Registration Form - completed #

I need to trim the registration form down to one page.  Well I started removing the steps and I realized that the review page uses the session container.  Perhaps I should keep it only for that purpose.  However the review page is pretty ugly. Also don't need to review their email and name.  I am confused as to why NetBeans says the Rbac Manager use Zend\Permissions\Rbac\Role as RbacRole; and use User\Entity\Permission; are not used.  I guess it was just an accident to include those lines.  I removed all such unused lines in the services and service factories of the User module. 

Now I need to do something with the data that the user submits.  Well I can play with with the reset password form because it sends an email.  I need to get email working on my local machine so I can test the registration.  Then I can use the generatePasswordResetToken as a template for a function like  generateRegistrationConfiramtionToken.  Before I do that I will set up email on my local machine.

Well the email is working but the reset-password link I get in the mail results in a 
A 404 error occurred
Page not found.
The requested URL could not be matched by routing.

No Exception available

OK.  I just needed to add /users/ to the url in the email.  Everything is working.  Now I can create the generateRegistrationConfiramtionToken fuction.  

Well I watched The Social Network this weekend and decided to make Policy Bubble closed source for business purposes.  If it is open source I am essntially giving away all profits that I may have.  

Now I need to implement generateRegistrationConfiramtionToken.  Should I create a user record in the database before the user confirms their email?  Should I put a captcha on the registration form?  I think I should just add the user.  I am going for simplicity.  Is success.html a good name for the registration succes view? Should I call it registration success? Nah.  I am not going to put too many view in the user controller.  

I finished adding the user resgistration and I added a link to reset the password.  The problem is that if somone enters somone else email they can log in.  I need to create a confirmation email so that only a user who has the email can register.  

I am setting up the registration confirmation email and realized that I don't have an email like registration@policybubble.com.  I can set that up later.  For now I should just use the project Gmail address or my email.  

Is it wise to check if an active user exists when the user tried to register?  Why not check if the inactive user exists as well?  Well if they never confirmed it would give them the change to confirm again.  However if somone registers with that email it blocks the real user. 

OK.  I finished the user registration code.  I would like to put it in a separate controller though.  Should I separate some of the usermana manager functions that have been duplicated?

I really don't want to create the user until they confirm their email.  That is because what if the user wants another confiramtion email?  

I am trying to fix the logic for blocking users out.  I just want to check if there is an active user.  If there is then I will say the user exists.  

NetBeans is telling me that I have too many nested blocks in the controller function.  Should I ignore this?  I am not going to nest it any more.  Yes I should ignore it.

# Blogs - completed #
Now I need to add the blogs.  I can put a link to the blog manager in the menu so user's can manage their own blog.  It should be called Blog not admin.  So in the read link I can put the blog code.  In the blog menu item I can put the blog manager.  I would like to add author links to the blogs.  I would like the users to have nice user pages.  Well the core functionality of the site is going to need a lot of work.  However I am going to work on it piece by piece.  

Now I am going to put the menu item. 

I have implemented blog functionality and it is clear that I am in the thick of it.  This is very much what facebook does.  I give credit to Mark Zuckerberg for being able to write facebook while he was in college.  Anyway I need to continue programming and I need to have a well defined plan.  I have implemented most of the functionality of the ZF book.  However I am still tweaking the navigation.  I will also need to make it so that the user can only manage their own posts.  Let me finish of this navigation and then move on to that.

## Fix naviagation - complete ##
The naviagtion is heavility dependent on the functionality of the site.  I need to wrap up what I have now and make it consistent.  When there is more functionality I can update the navigation to arrive at that functionality.  I just think it is a little confusing.  Maybe the user won't understand that the blog has posts from everyone. I think I should call it news instead of blog.  That is what most people understand the facebook feed as.  Also I think the post manager is like the facebook profile.  It is basically a news feed just for that person.  The person can edit their posts there just like the in the post manager.

Of course the post manager/profile page would have the persons profile picture and more photos and info.  That will come with time.  Of course the news feed will allow the user to post just like on facebook.  Also the home page might go straight to the news feed after the user logs in.  I'm glad Facebook figured all this stuff out of the years.  It sure saved me a lot of work and worry.  Anyway the main thing I am worried about is spammers and trash in the news feed.  Perhaps I should also allow users to add friends.  That way they only get news from their friends.  However policy bubble was supposed to be open source.  Users we supposed to only allow people they wanted to use it.  They would have networks.  So policy bubble would need networks.  Like world network, usa, maryland, silver spring etc.  Basically these are filters.  I could add filters to the posts.  Rather than just tags users could specify what network they want to post to.  This seems like a lot of work.  I need to take it one step at a time. Right now everyone will be in one network.

It's becoming apparent the design of Oleg and my design are parting ways.  The url says pb/posts/add but the breadcrumbs say pb/blog/add post.  That is because Olegs blog is just for one person.  I have though about butting the blog in it's own module but for now we are ok.  The questions is whether I should treat posts as part of the blog.  The url indicates that they are not.  It's ok for now because I am going to add some filtering capabilities and permissions so I am free to order the hierarchy. 

In the end I don't think the posts will be under the blog.  Also I think the blog and the posts will move into their own modules as they grow.  It's fine for now though how the navigation is.

## Permissions - completed ##

A user should only be allowed to manage their own posts. I need to make sure that a user can only manage their own post.  How am I going to do that? Well I think the user should be passed in so the controller knows which posts to get.  Also only a logged in user should be able to see their own post.  The questions is whether this is going to be based on user id or what.  Also will it be passed into the url?  

It looks like the dynamic assertions.  I think I should go home an take a break and think about this tommorrow.  I need to figure out how to use the query returned by the PostRepository.  Ok.  Now I need to make sure that when I create a post I will add the user ID.  Where is the create post function?

Well now I can go to http://policybubble2.local/posts/edit/3 and edit this post now matter who I am.  This post was created by one of my test accounts.  How can I make it so that only that account can edit this post?  Well I really think that I need to use permissions.  I know that permissions are working for the admin page but let me check.  I have confirmed that only the admin can access the admin pages.  However the edit pages are shared.  How can allow a user to edit only their own page.  Perhaps I can query and see if it is the user's post.  If it is not I can send them away.  The ZF3 book mentions the following roles

post.view	View any post.
post.edit	Edit any post.
post.own.edit	Edit only owned posts.
post.publish	Publish any post.
post.own.publish	Publish only owned post.
post.delete	Delete any post.

How can I use these to allow a user to edit only their own post? I will need to update the  access_filter key in the configuration to say ['actions' => ['edit'], 
                 'allow' => '+post.own.edit']
                 
I am also thinking that I should make the posts page so that it uses the profile.own.view permission like the settings page does.  The thing is that if somone wants to share their posts page they can send the link.  Well let's take it one step at a time.  Now I need to  add this to the RbacAsserionManager
if ($permission=='post.own.edit' && $params['post']->getUser()->getId()==$currentUser->getId())
            return true;
Also I had to create the post.own.edit permission and assign it to the Guest role.  Now it is working.  The assertion manager gets the post from the viewModel.  Since the Module.php file in the user module runs this on dispatch it has access to the variables in the view model of any controller.   

Are there any other permissions I need to modify at this point?  Yes!  Apprently a user can enter http://policybubble2.local/posts/delete/4 and delete a post from another user.  Post 4 was created by one test account but I was able to delete it while logged in as a different test account by putting the url in the address bar.  Let me create permission called post.own.delete, assign it to the Guest role, update the access_filter in the config and add
if ($permission=='post.own.delete' && $params['post']->getUser()->getId()==$currentUser->getId())
            return true;
To the assertion manager.
Great!  It is working.  Are there any other permissions I need to update?  So far things are looking good.  I would like to allow the posts to have photos.  Also I would like to imlement the feedback form.  Why don't I implement the feedback form so that I can have all the buttons working on the site.

# Feedback form - completed #
I am going to finish the feedback form so I can have all buttons working on the site.  Apprently the permissions are not working.  How can I fix them?  

# Fix permissions - completed #

I realized that the post.own.edit persmission is not working.  Perhaps it's because I had only tested it with the admin user.  I realized that it doesn't work for guest users.  In fact it didn't work for admins either.  It's just that admins do not have that permission at all.  I should also test the post.own.delete permission with a guest user.  OK.  I just had to add the authentication check to the controller so I could pass in the parameters.  What the heck.  Now I cannot even edit my own posts.  What is going on here?  I had post.own.view not post.own.edit in the controller as the check.


#Fix session error - completed #

An error occurred
An error occurred during execution; please try again later.
Additional information:
Zend\ServiceManager\Exception\ServiceNotCreatedException
File:
C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php:765
Message:
Service with name "Zend\Authentication\AuthenticationService" could not be created. Reason: Session validation failed
Stack trace:
0 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(200): Zend\ServiceManager\ServiceManager->doCreate('Zend\\Authentica...')
1 C:\Apache24\htdocs\policybubble2\module\User\src\Service\Factory\AuthManagerFactory.php(24): Zend\ServiceManager\ServiceManager->get('Zend\\Authentica...')
2 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(758): User\Service\Factory\AuthManagerFactory->_invoke(Object(Zend\ServiceManager\ServiceManager), 'User\\Service\\Au...', NULL)
3 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(200): Zend\ServiceManager\ServiceManager->doCreate('User\\Service\\Au...')
4 C:\Apache24\htdocs\policybubble2\module\User\src\Controller\Factory\AuthControllerFactory.php(19): Zend\ServiceManager\ServiceManager->get('User\\Service\\Au...')
5 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(758): User\Controller\Factory\AuthControllerFactory->_invoke(Object(Zend\ServiceManager\ServiceManager), 'User\\Controller...', NULL)
6 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(200): Zend\ServiceManager\ServiceManager->doCreate('User\\Controller...')
7 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\AbstractPluginManager.php(141): Zend\ServiceManager\ServiceManager->get('User\\Controller...')
8 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-mvc\src\DispatchListener.php(95): Zend\ServiceManager\AbstractPluginManager->get('User\\Controller...')
9 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-eventmanager\src\EventManager.php(322): Zend\Mvc\DispatchListener->onDispatch(Object(Zend\Mvc\MvcEvent))
10 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-eventmanager\src\EventManager.php(179): Zend\EventManager\EventManager->triggerListeners(Object(Zend\Mvc\MvcEvent), Object(Closure))
11 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-mvc\src\Application.php(332): Zend\EventManager\EventManager->triggerEventUntil(Object(Closure), Object(Zend\Mvc\MvcEvent))
12 C:\Apache24\htdocs\policybubble2\public\index.php(40): Zend\Mvc\Application->run()
13 {main}
Previous exceptions:
Zend\Session\Exception\RuntimeException
File:
C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-session\src\SessionManager.php:162
Message:
Session validation failed
Stack trace:
0 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-session\src\AbstractContainer.php(78): Zend\Session\SessionManager->start()
1 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-authentication\src\Storage\Session.php(63): Zend\Session\AbstractContainer->_construct('Zend_Auth', Object(Zend\Session\SessionManager))
2 C:\Apache24\htdocs\policybubble2\module\User\src\Service\Factory\AuthenticationServiceFactory.php(23): Zend\Authentication\Storage\Session->_construct('Zend_Auth', 'session', Object(Zend\Session\SessionManager))
3 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(758): User\Service\Factory\AuthenticationServiceFactory->_invoke(Object(Zend\ServiceManager\ServiceManager), 'Zend\\Authentica...', NULL)
4 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(200): Zend\ServiceManager\ServiceManager->doCreate('Zend\\Authentica...')
5 C:\Apache24\htdocs\policybubble2\module\User\src\Service\Factory\AuthManagerFactory.php(24): Zend\ServiceManager\ServiceManager->get('Zend\\Authentica...')
6 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(758): User\Service\Factory\AuthManagerFactory->__invoke(Object(Zend\ServiceManager\ServiceManager), 'User\\Service\\Au...', NULL)
7 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(200): Zend\ServiceManager\ServiceManager->doCreate('User\\Service\\Au...')
8 C:\Apache24\htdocs\policybubble2\module\User\src\Controller\Factory\AuthControllerFactory.php(19): Zend\ServiceManager\ServiceManager->get('User\\Service\\Au...')
9 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(758): User\Controller\Factory\AuthControllerFactory->__invoke(Object(Zend\ServiceManager\ServiceManager), 'User\\Controller...', NULL)
10 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\ServiceManager.php(200): Zend\ServiceManager\ServiceManager->doCreate('User\\Controller...')
11 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-servicemanager\src\AbstractPluginManager.php(141): Zend\ServiceManager\ServiceManager->get('User\\Controller...')
12 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-mvc\src\DispatchListener.php(95): Zend\ServiceManager\AbstractPluginManager->get('User\\Controller...')
13 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-eventmanager\src\EventManager.php(322): Zend\Mvc\DispatchListener->onDispatch(Object(Zend\Mvc\MvcEvent))
14 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-eventmanager\src\EventManager.php(179): Zend\EventManager\EventManager->triggerListeners(Object(Zend\Mvc\MvcEvent), Object(Closure))
15 C:\Apache24\htdocs\policybubble2\vendor\zendframework\zend-mvc\src\Application.php(332): Zend\EventManager\EventManager->triggerEventUntil(Object(Closure), Object(Zend\Mvc\MvcEvent))
16 C:\Apache24\htdocs\policybubble2\public\index.php(40): Zend\Mvc\Application->run()
17 {main}

Every once in a while I get this error when I try to use policy bubble.  In the past I saw it was a session issue and I thought maybe I should clear my cookies.  That worked but now I am getting it again.  I can't have my users with this problem.  I need to get to the bottom of this.  I read the stack traces examined the code and realized it has to do with the session validation.  There are a coulple of session validators in the global.php file.  When I stepped through the code I saw that the session id is "ed572ec636d63b4e1889d163c08a1468".  In the apache folder this is a blank file.  Maybe this has something to do with the way apache is configured.  I am using a shared Apache configuration on this machine that I did not set up myself.  My questions is why do I get a blank cookie every once an a while?  I have experienced this with another application as well.  Perhaps I should just clear my cache when this happens and take for granted that this Apache setup uses an unusual session configuration.  When I deploy policy bubble I will probbaly not have these problems.  However I would still like to see if I can fix it.

Well I deleted the empty session file on the server and it is working.  I need to monitor this in the future and see why this file is getting created.  Probably caused by another application's single sign-on on the same machine. 

# Photos on posts - completed #
I want to allow users to add photos to their posts.  There are a number of features I would like to add to the blog as well.  I would like the commens to take the user's ID so that it can be linked back to them.  I would also like people to be able to add photos to comments.  Also I would like to create a profile page for the users.  I want posts to say who the author was so it can be linked to the profile.  Facebook has the posts page and the profile page as the same page so I may do that.  However I will need to make the user ID as part of the url.  Also facebook allows users to customize their ID.  I should allow that as well.  That way if a user wants to share their page they can send the url.  Those are my thoughts for now.  Also I would like to add a search bar.  Also I would like to add data visualization features.  Also I want users to be able to change their account settings and close their account.  I'm not sure I want to alert users how many posts and users their are in the entire database.  I may make the user ID a hashed version of the plain user ID.  Same with the post ID.  I may use a hashed post ID.  Also one suggestion I received is to allow users to add or pop bubbles on posts.  If I want to add a visualization tab I think it would be a good thing.  I could filter on location, rating, tag, network, connections etc.  Of course the posts would have to collect this information. The blog and profile could have these filters as well.   That kind of solves my privacy problem.  I think I should add a visualizations pane on the homepage but I should keep the feedback pane.  I may even want to make a feedback tab where users can criticize the site.  I would also like to add economic analysis.  Perhaps users could add prices to their posts.  Also they could add an ethical rating.  So many ideas.  I should make the feedback public.  Or I could make the feedback private.  I should allow the user to choose.  

Now I am going to enable adding photos to posts.  How am I going to do this?  One step at a time.  Hmm.  I thinking I should allow an entire image gallery to be assocated with each post.  I mean.  Why not?  I could easily attach a video gallery as well.  I could just copy the image gallery code from the ZF3 book.  Well maybe I can make it like facebook where you have profile images.  I think the easiest to keep it like the ZF3 example.  I should put the image gallery link on each profile page.  I think it is going to be hard to complete with twitter and facebook photo functionality if I don't use javascript.  The user should be able to scroll in the blog and see top photos for the post.  I think each post should have an image gallery.  Also I think I should make a multi-page form for creating a post.  In the second page users can upload photos.  When they submit the form the photos can can be added to the post.  If they don't submit the form the photos can be deleted from the temp location.  The ZF3 book doesn't even have an option to delete photos from the gallery.  I would have to add a remove button.  Perhaps I should treat photos like posts.  I could have have a photos controller that allows for adding, editing, deleting photos.  I could add tags.   

Well. I would have to treate photos like tags.  You can have many per post.  I would have to create a photos table with a post_photo table that links them.  The image gallery would have to appear on the add, edit and view post pages.  The funny thing is that I am trying to do this without JS.   I think that is ok while I am implementing all the backend stuff that JS could be used to improve UX.  So I should do a multi page form.  On the seond page the user gets the photo gallery.  I could also skip all this photo stuff and go straight to videos.  I could require the user to have one video per post.  That way they can add sound, images, etc.  As a musician I think videos are a lot cooler than photos.  If users want to add photos they could also just put in links to the videos.  Perhaps I can have one photos and video per post.  After all the point of PB is to improve policy not show images of your cats.  

So let me start with add one photo per post.  

I was looking at some examples.  I looked at Twitter and Facebook.  Those are JS heavy.  Craigslist has a great example of uploading multiple photos to a post using only back end code.  I can implement this with ZF3 if I had a multi page form or a single page form.  I am going to explain the multi page version first.  Then I am going to explain the single page version.

Craigslist uses a multipage version.  This is like the multipage form example by Oleg.  All of the form data is stored in a session container when the user goes from page to page until the form is submitted.  On the photo upload page there is a file selector and an area for photos that have been uploaded.  When the user uploads a photo to this page the controller will store it in the session container and show the photo and ask the the user wants to add more.

I can do this on a one page form as well.  There can be a button that said upload photo.  When the user uploads a photo the page will call the controller.  The controller will see if the action is a submission or to upload more photos.  If it is to upload more photos it will add the photo to the session container and redisplay the form and the photo.

You know what.  I think I should make this a multi page form.  PHP is a multipage application.  The pages are the views.  Therefore I should not have pain showing the upload photos view.  However I should not make it mandatory.  

I would have to add another table to the database called post_photo.  Since there is a one to many relationship between posts and photos this table will have to store the references.  I will also need a photos table.  Does the image galler example have a photos table?  Well the image controller simply gets all the images in the image directory.  It will have to be updated to get the images for the user.  Actually the postcontroller will have to get the images for the post.  I think the functinality of the image controller can be moved to the post controller since the post controller will be handling images.  The image manager will be what does most of the work.  Especially the getsavedfiles method.  This will have to be modified to get the images for the post.  The post controller will say which post it is then the getsavedfiles method will quiery the post_photo to get the image path names.  So one thing to consider is that I could easily add on picture per post.  I would just have to add a file input to the post form.  However the image filter can set the storage location.  In my case I would have to rename the file based on the post id and the photo count.  Maybe I don't need a post_photo table.  I could just create an image directory for each post and get the images in that directory.  

Instead of trying to plan all this out I think I should put in the photo gallery and then adjust it.  After all there are some modifications I need to make to the photo galler itself such as deleting photos.  Also I will need to make sure image uploads are working.  I could then tweak it to allow image uploads on different pages.  So what I am going to do is. Add an image panel to the home page and a menu item.  I am going to add the image controller and all associated functionality.  Then I need to get the remove image functionality working.  Then I need to get it working for the logged in user.  After that I modify it for posts.

OK.  I have the image gallery and you need to be logged in to use it but it is still shared.  I need to get it to display the photos of the user that is logged in.  By the way.  The blog is shared by everyone and the posts page allows users to post to the blog.  I was thinking that the image gallery should shared by everyone and the upload page should be allowed for logged in users.  However it provide no benefit if users cannot manage their own photos.  Perhaps I can create a gallery page that is like the blog and an uploads page that is like the posts page.  That way everyone can see the images of all users in the gallery page.  Or what I should do is just let the users add one image to their post.  However it will be hard to change or delete this image.  I really want the gallery to be in the post level.  Well I can keep it how it is an let the user add an image to their post.  I can always delete the image gallery if I want to.  I am just trying to find a way were the functionality of both can meet midway.  I allow users to add a photo to their post I will be one step closer because I will not only have photo by user but I will have photo by post as well.  Then I can do gallery by post.

OK.  So I now am displaying the image gallery in the post view.  Also the add post and edit post have an image selector.  The add post action actually does let me add images to the central gallery.  Howeber the edit post action gives me a validation message.  It says the field is required.  I wonder why that is.  Is that because this data is a prepulated form.  Perhaps something is overwriting my file selection. OK.  It's that I did merge the file info like I did in the add post action.

Now I am going to commit because I am happy with the fact that I have multiple photos in phone post.  However I will need to keep modifying this.  Right now the photos are actually being tracked by git.  I need to remove these.  

OK.  So what is my next move?  I need to be able to get the images for the post.  Should I get one image per post since the add post form has a selector for one image?  Should I update the add post form to be multistep and upload multple images to the post?  I do like the add more button.  Perhaps I can put the add more button in the edit post form.  Actually what I need to do is put the image gallery in the edit post page.  Then I can do the upload more button. Yes. I should put the gallery in the edit post page.  The question is whether I should load these photos where the post data is loaded or not. In the add post method there is an if to check if the request is a git request.  In the edit post method there is an else statement.  Why can't the edit post method load the form data after the request =  post if?  If it did then if it was a post  and the form was not valid then the submitted data would get overwritten.  In the add post method there is not else because no form data is being set from the database.  So for the image gallery I am setting data from the database.  I should set it in the same spoit.  If not then the images that the user has entered would get overwritten with the data from the database.  However I want to get the files every time since I am just trying to display images at this point.  I am going to put it at the end of the function for now.

Displaying the gallery in the edit post page is a bit more difficult because this page is a form.  I could display the gallery above or below the form but the image file select would be far away from it.  That is not intuitive.  What should I do?  Right now I am going to display the images below.  I will work on this later.  

Is it a good idea to put the gallery in the edit post page?  Do other web sites do that?  Yes they do.  They have to.  How else are you going to know what photos are in your post.  So I am going in the right directtion.  However I should probably make it multipage since I am only using PHP.  I will work on this later.

The photo gallery is not working on my laptop.  It was not working on my laptop for the form demo either.  I can upen the full size images but the thumbnails display a broken image icon.  What does that mean?  It's just that I had not created the /tmp dir.  Now I am good to go.

Well since I need to make the edit post form a multi page form I will need to take the gallery out of the edit page.  OK.  So what I am going to do is create the view that will be part two of the edit form.  I will put the photos gallery here including the add more button.  Once that is in place I can update the controller to put the uploaded photos in the session conainer until the user says done.  Actually I can just upload the photos directly to the gallery but this will need to be changed or else this form won't work.  

I think instead of adding steps to my form I should just add steps to the controller and use the post and image forms separtaley.  Well actually since the submit button won't come until the last step it would be a good idea to put it all in one form.  

I am a little bit confused about what to do in the controller.  Ok.  I understand what is happening.  

I drew some wire frames about how I should move forwards.  It's good I used the multistep form example because I will need the session container but I can do everything on one page and have the submit button at the bottom.  I would need two extra actions though.  I would need an uploadPhotoAction and a deletePhoto action.  I would have a button on the form below the file selector that says upload.  When the user uploads the photos that they have selected it could save the photo to the session container.  However what actuall happens is that the photos is validated and then filtered.  When it is filtered it is moved to the storage location.  What I could do is reasearch other filter options.  Perhaps I could move the photo to a temp place near the permanent post photos.  When the user submits the form I could move them to the permanent place.  Also on the initial step of the form I could clear the location.  

Or I could create a temp place and store the uploaded files in this place.  I think the data/upload place is fine for now.  I can change this letter and delete all the photos here to test the edit action.  It's better than nothing.  Later I can give it it's own place before and after form submission.  Anyway I can use the uploadphotoaction just to upload the selected file to the place.  It won't even count the form steps.  It will just reirect back to the form.  The however I will need the session container.  I will need to count steps.  I could even go to the edit action with an upload parameter.  If the parameter is there I could add the photos.  I think I should keep it a multi page form just so I don't have to undo code.  I can make it a one page form when the multi page form is working.

Anyway the upload action will just validate the form and the user back to the edit action.  There will be another image in the gallery but the form will not be submitted.  This is an improvement.  The uploadImage action could take the action to return to as a parameter.  For example the add post or edit post action.  However this method would not be called unless the action is a post.  I can see that the upload action from the form controller has code for when the action is not a post.  How should I deal with this in the post controller?  Should I create a view?  Access controll?  Disable the view?  Should I put this code into the edit action.  No because the edit action is going to increment the step.  I don't want to do that until the next step is ready.  The next step is the end actually.  The end is not ready untill all the photos have been chosen.  So I think I can create a view just so I can move things along. Later I can cut off all the access that should not be allowed.  

I am beginning to realize I have a mix mash of functionality going on.  I like my most recent deveopment but I think that I should probably use two separate forms.  Or what I can do in the edit controller is see if a user has uploaded an image.  If they have I can go back to step two.  If not I can finish.  That is pretty straight forward.

Ok.  I have made good progress.  I can add photos to the photo gallery using a multi page edit post form.  Now what I need to do is get the gallery to be unique for each post.  How can I do that?  Well currently the photo is added to the file system when the file is validaded.  Perhaps I can pass in the name of the post to the form so it knows where store stores the file.  I can create a folder to for each post.  Within that I can create an edit folder that is used to edit the gallery.  I can copy the photos to the place and use it while the user is using the form.  Let me see what file filter are available though through Zend.  Well the options are limited.

So what do I need to do.

Upload
        Post
                 1
                     temp
                         Image1.jpg
                         Image2.jpg
                    perm
                        Image3.png
                        Image4.png

I can put a post folder in the upload folder and have a folder for each post id.  In each post folder I can keep the permanent file in the perm folder.  I can create a temp dir that is used while the post is being edited.  I can copy the file from the perm to the temp dir.  This might not be the most efficient solution but it is the most intuitive for now.  Later I can find heuristics.  

So I am using the image manager to get the temp file and and save the permanent files.  I am a but confused on how I should do that given my planned directory structure.  Should I rename the directory?  Do a recursive copy?  Can I do a recursive delete?  

In the get temp files function I cannot delete all the files in the temp dir every time the page is revisted.  It is ok to it the first time the page is visited.  How can I solve this problem?  I could send the step.  Or I could store in the session container a flag and set this flag on step two.  That way I will now when the first time this page has been visted occurs.  I can pass this flag to the get temp files function.

Well I finished the unitque post photos for post edit.  Now I need to fix a vew more things.  I think I should delete the temp dir when it is not in use.  I need to go home now but when I get back I can do the new post form so it can upload photos.  Also I need to update the delete post button to delete the photos.  Then I need to get rid of the image gallery that was just an example.  Then I need to clean everything up and finish this task.  I need to get rid of unsuded code, update comments..etc.  Also I need to work on the delete photo one by funcionality in the new post and edit post form.  Also I should limit the number of photos the user can upload.  

I was trying to demo the new features but they are not working on my laptop.  The files to get uploaded on form validation and the session container does not have the step one data after step two.  So I am not getting any photo uploads or step one data.  I reset to the commit where it was working on my work computer and I still get the same behavior.  For that reason I think it is my laptop.  I don't know what could be causing the issue.  I did some debugging and the answer was not clear.  Sometimes I got error messages that said I had no input on both steps.  Other times I everything was valid.  I was debugging with IE though and will try again with Edge.  It's hard when you have several sessions going on.  My main question though is why are the file not being uploaded on validation and why does the post manager not get the step one data?  I have not I idea.  I am going to sleep on this one.  I tried in an incongnito window and it's the same.  However I see why I was getting an invalid photo some times.  I was choosing one that was too big for php ini.  That is the form validation I got before and now I confirmed it.  As for the step one data being empty I think maybe it is getting overwritten somtimes.  If I change the upload path in the post form the image will actually get uploaded to that location.  So it is not liking the new path.  

I am having a problem on my work compuer as well.  I fixed it.  'loc'=>'perm' was on the edit page.  I changed it to temp.  I'm still not sure what is going on on my laptop.  The code is fine.  I has got to be something with the php config.

Well I finished the add post photo upload.  I did not save this wiki and I refreshed the page so I lost some notes but my last commit was 42bdbf2.  So now I need to make it so that photos can be deleted during the photo edit process.  This should be easy.  I can just create a new action in the image controller called deleteEditPhoto.  It will take the photo name and post ID and remove that image from the temp dir.  Then it will redirect back to the edit image page.  Easy as pie.  However I will need to find a suitable delete button that I can put in the view.

OK.  I finished the remove individual files in post edit.  Now I need to do it in post add.  Done. 

I am trying to fix my laptop again.  I reinstalled PB and I am still getting problems.  I don't think it is the code bc it works on my work computer.  So if it is not the code it is probably the server.  However I need to diagnose the problems.
1.  Photos not uploaded to temp locations but uploaded to photo gallery
2.  Session container does not have data from step one on form save
3.  Route param does not exist for second step

What do these three things say about the server?

1.  Only certain places accept uploads
2.  The session container is not working
3.  The route params are not working

These are still not pointing to a clear answer but I do think it is the server.  How can I fix the server?  I really don't know why.  This is so sad.  I give up.  Please help me.  I have a degree in CS and I know how computers work.  I can debug at the lowest levels.  Maybe it is because I am on a windows machine.  That kind of limits how low I can go.  However I doubt the problem is at the operating system level.  However I should be able to check if I want.  So what is happening at the machine level?  Things are not how I want them to be at the machine level.  My job is to find it.  I need to go lower.  I need to see why the route param is not being passed.  I am going to debug into the response code.

I debugged and I see the route was working but the session.  So there are two indicators that the session container is not working on this computer.  Now I see what is happening.  Is true that the session catiner is not holding the data.  OK using my super duper CS skills I should be able to see exactly why it is not working.  Again it is a hardware thing.  I need to go low.  The session container is stored.  I need to stop looking in the PB code.  How can I test that the session container is working?  Well I know it is not working.  So how can I get it to work?  I decided that I should try it out on the Linux box.  Whether or not it works there could tell me a little more about the problem.

I am going to limit the number of photos a user can upload.  I just don't want them to be able to upload an infinite amount.  What number should I set?  10 is too low.  25 is better but still stingy.  Maybe 50.  However I think 100 is a good amount.  It's a lot of photos.  Where should I check the photo uploads?  I think a good spot would be while the user is uploading the photos.  I can check and see if it is more than 100.  If so I can say hey.  You arleady have 100 photos.  I can raise an form message.  I just realized that I could upload multiple files.  Do I want to do this?  I could use the file count filter.  However I still would want to verify that the post has a limited amount of files.  Perhaps I should should continue with the one by one version.  In the future I could do the multple upload version that would just loop around what I have.  That is pretty easy.  Also I would like to test this on a mobile device.  I think it would still work though.  So how can I check the upload dir count.  PHP count files.  The problem is that the files are uploaded when the form is validated.  I don't think I should worry about this right now.  Don't even have any traffic.  However I should see if I can do the multple upload.  That way I can delete all the files and put a form validor.  That it how I should do it. 

When I upload multiple files I get an error that says the dirname should be a string and that an array was given.  Now even the indivudual file uploads are not working when the debugger is off.  That makes me pretty mad.  It's as if PHP is not working. Well I have some good news and some bad news.  The good news is that the site works the same as on my laptop.  The bad new is that it is broken like it was on my laptop.  Why are the files not uploading?  It only works when xdebug is enabled.  Not true.  Well xdebug does not seem to be working with PHP 7.2  It has not even been released.  However   I was still haveing the same problems.  Back to 7.0 and the uploads and removes work only when xdebug is on.  That is so weird.  But the add post form always stays on page one.  

Well I noticed some trickly logic in my post controller.  The userChoices variable was not getting reset on the first step.  However I have a stepParam and a userChoices['step'] and a $step.  It is getting confusing to manage all of these.  I need to remember that the stepParam is just to see if the request is coming from the continue button.  In that case I would set the step to the userChoices['step'] .  I think that stupid step param is messing me up.  When you submit the form it is not going to be set.  So the sessionContainer is getting reset with my check.  

It looks like everything is working.  I am going to skip the multiple upload and file count check for now.  I would have to plan for it a little better in the future.  I did not plan for these features so implementing them is not easy.  Later I may do it when the site grows.

Well.  If I want posts to have the author's name I can't have visitors seeing the the edit and delete buttons.  Something tells me I should do automated testing for the code I already have.  However my testing certification informs me that I can't test everything.  I just should do the most important tests.  Right now since I am the only one working on this I think it would be safe to continue developent without atomated tests but I should probably refactor the code I have so far.  Well as long as it's working and it makes sense to me then I should not mess with it.  If I mess with it it could become confusing as I start renaming things and compining code.  It's better to keep things short and simple.  

# Comment Photos - completed #

(start thoughts from previous task)
I want to allow users to add photos to their posts.  There are a number of features I would like to add to the blog as well.  I would like the commens to take the user's ID so that it can be linked back to them.  I would also like people to be able to add photos to comments.  Also I would like to create a profile page for the users.  I want posts to say who the author was so it can be linked to the profile.  Facebook has the posts page and the profile page as the same page so I may do that.  However I will need to make the user ID as part of the url.  Also facebook allows users to customize their ID.  I should allow that as well.  That way if a user wants to share their page they can send the url.  Those are my thoughts for now.  Also I would like to add a search bar.  Also I would like to add data visualization features.  Also I want users to be able to change their account settings and close their account.  I'm not sure I want to alert users how many posts and users their are in the entire database.  I may make the user ID a hashed version of the plain user ID.  Same with the post ID.  I may use a hashed post ID.  Also one suggestion I received is to allow users to add or pop bubbles on posts.  If I want to add a visualization tab I think it would be a good thing.  I could filter on location, rating, tag, network, connections etc.  Of course the posts would have to collect this information. The blog and profile could have these filters as well.   That kind of solves my privacy problem.  I think I should add a visualizations pane on the homepage but I should keep the feedback pane.  I may even want to make a feedback tab where users can criticize the site.  I would also like to add economic analysis.  Perhaps users could add prices to their posts.  Also they could add an ethical rating.  So many ideas.  I should make the feedback public.  Or I could make the feedback private.  I should allow the user to choose.  
(end thoughts from previous task)

So the next idea I had was to have the comments take the user's id or name so it could be linked back to them.  However the user's don't really have a profile page.  I haven't even made user profiles as a main feature of policy bubble described on the main page.  However I don't really wan't people to be the center of policy bubble.  I don't want people to go to other people's profiles and post on them like they do on facebook.  I want policies to be the center of policy bubble.  I want people to be able to post to other people polcies.  In fact.  Maybe the user should be anonymous.  I want people just to focus on the policies.  So what I could do is the next step in my thoughts above.  I could allow people to post photos to comments.  

Should I allow more than one photo?  Facebook just let the user put on photo per comment.  I think I should do one photo per comment.  Also I need thumbnails to appear on the feed and post admin page.  Once I do these things I can move on to bigger and better things.  Also I think it is rediculous to display the image name below the image.  I am going to get rid of this right now.  Done.

OK.  Now I am going to put an upload button in the comment form as well as display the image in the comment.  I should also add a remove comment button.  To do that I will most deinitley have to link comments to accounts.  

So I am looking at the DB ER diagram.  I am also looking at the data>upload file structure.  I am trying to decide what I should do next.  I added a file input to the comment form but I have the destination as the data>upload folder.  I will most definitley need to store the photo for each comment in the data>upload>post>comment folder.  After the form has been validated I can create the comment and then pass the comment id to the storeCommentPhoto() imageManager function.  It can rename the photo with the id of the comment and store it in the data>upload>post>comment folder.  If a commen is deleted I can delete the corresponding photo as well.  

So as far as the code is concerned I will have to store the photo to a temp location.  That is statandar.  I can just take the photo from the temp location and then rename it.  However I will have to review how that is done in the ZF3 book.  

I am thinking I should allow multiple photos for comments.  The reason is because I am passing the comment id to the saveCommentFile() function.  It's kind of hard to give the file the name of the comment id because the renaming is usually done through the form filter. Actually I think move_uploaded_file() can take the desired file name.  I think adding multiple files would be easy though because I have already implemented that code.  I would just have to move it to the view post action.  The question is whether or not I want to allow users to add multiple files to a post.  By they I still want to use the multiple upload feature.  Uploading one photo at a time is so 90s.  I think it would be a good idea to allow multiple photos per comment.  I think that is because people usually have opinions about policies and should be able to say as much about somone else policy as they can say about their own.  Also the text to photo ratio would be the same.  I am going to allow multiple uploads.  

Ok just adding the multiple option to the input works but after I click continue the post saves.  I would like it to not to save unless no file is chosen.  I am just trying to see whether the user uploaded photos or not.  It was easy when I only allowed one photo but now it is a bit harder.  How can I do it?  What the heck.  I am using the debugger and I am doing the obvious things like checking if count of the file array equals 0 and it is still not working.  Got it.

Now I have multiple select working but I have nothing preventing the user from uploading a very large amount of photos.  How should I prevent this.  The count form validator is ok but they could just upload the max several time.  It would be better to check the number of files in the temp dir.  If it is more than 100 I should add a form error message that says there are too many files.  

I need to go now but when I get back I need to keep working on my max file count validator.  Then I can finish the comment photos upload form.

I noticed a few weird things with the validator.  First of all.  It runs the validator for each file selected.  Second each time it runs it checks the temp dir file count.  If there are 4 files but 12 were selected it is going to be valid for each of them.  It's not until the second time that this will be noticed.  I need to add the temp dir file count and the also the number of files in the $Files array.  That way I can see if the user is trying to have more than 10.  

However I don't think I should check the file count for every file chosen.  Well I can do break validator chain on failure.  That would still run the check for all files for a valid form.    I could add a hidden field and check the file count here. How can I access the form data in the validator.  

I am having trouble moving forward.  I just want to check if the temp dir file count and the number of files the user is trying to submit is greater than 10.  I could do it in the controller.  I could just use the post manager and check how many files the user submitted and check the temp dir file count.  If the sum is more than 10 I could.

I get this error with the file count validator 
Warning: dirname() expects parameter 1 to be string, array given in C:\Apache24\htdocs\policybubble2\module\Application\src\Validator\MaxFileValidator.php on line 201
if (($file !== null) && ! array_key_exists('destination', $file)) {
            $file['destination'] = dirname($value);
        }
What is it doing?  $file is basically the form.  It says if the form is not null and there is no destination key in the form then the destination key is directory name of the value.  The value is  first file.  Why does it want to set a destination key for the form based on the dir name of the first file?     It eventually wants to add the file to the list of files and then count them.  I think I could just skip these checks and use the data it has.  It has all of the form data in the file array.  I can even just count the array length directly.  

OK.  I finished the max file upload for post edit.  It still checks for every file but there is nothing I can really do.  Using the file validator is the only way I know to get file data and set the form messages.  I think the count validator was designed to run that way.  It will run for each file selected as long as it has not descovered that there are too many files.  Is there a way that I can get it to run for only the first file?  Well it gets attached to each file.  I with I could attach it to the hidden element.  I am just going to leave it.  It may not be the best but it works.

Now I am going to do the max upload for the new post.  It is saying that one file exists although I have 0.  

Ok now that I have the max file upload validators I can do the comment photos.  Again I am wondering if I should do just one photo in the comment or many.  I should to 100 for the post and 10 for the comment.  That way I can easily scale down if I want to.  Also I don't want the site to put obvious limitations on the users.  Or maybe I will do 30 and 5.  It doesn't matter.  What matters is that the site can handle changes in configuration.  

So what I need to do now is.  

However I think I should should display thumbnails on the blog and post admin pages.  Perhaps I should display the first two or three and say how many more there are.  I don't think I will do this with the comments since there is no comment page.  I will just show all the photos.

Anyway how can I do this?  I think I should do it just like I do it on the view post page.  I acutally loop through the files names and display the images with a source that calls the image controller to get the thumbnail. So I would have to essentially add this code to the blog first.  There is a bit of a problem.  On the blog page as well as the post admin page the posts are fetched using a query.  The view then loops through the array.  I will need to get the files for each post.  This is different than the post view page because we are getting the files for only one post.  So how am I going to get all the posts and get the files for each post?  Perhaps in the view and can do a loop that takes the post id.  Actually I can just look in the file system and get the pictures if there are any.  I can send the image manager to the viewModel.  There is already a getSavedFiles function in the image manager.  Instead of copying this to only get the first x files I could add a parameter that says count.  When I loop and can say while count <count.  However I already have code using this function.  How could I set a default value that does not restrict?  I could say count = .  Hmm  I think I will just copy the function.  

That is looking pretty good.  I don't really think I should say there are no images to display.  That is something I need to sleep on.  Selecting images is so built in to the post add and edit forms that it makes sense to say whether there are images or not.  Also it keeps the spacing between posts the same so you tell the posts apart easliy.  Also the view post page has this.  I think I will keep it for now and think about how I can better do this.  Maybe I should not make the image selection it's own page in the edit and add post forms but it is a lot to think about right now.  

Also I think I should maybe say how many images the article has but to be honest that is not really important.  It is pretty obvious that the blog shows the first images in the post.   I think the next thing I should do is add the thumbnails to the post admin page.  It gets me thinking if I am going to allow other files types like audio and video.  Also I wonder how I would handle links to other pages.  For example if you share a soundcloud song on twitter it plays the song on tiwtter if you want.  I should not worry about this.  I think I should do the post admin thumbnails.  After that I can decide if I want to allow videos before I do the comment photos.  

I see an immediate problem.  This page is a table of rows.  Perhaps I can have two rows for each post or I can put the photos in one row. I think I should start by adding a cell for the photos.  It's not bad but the images are appearing one below the other.  How can I get them to appear one next to the other.  By the way.  I should probably use the paginator on this page.  I really should not show all the posts here.  I will do that after I get the images side by side.  Well I limited the image count to one for this page.  It makes sense since it is a table.   I should do the paginator here and then decide if I want to keep the table form.  I'm not sure if I am going to allow users to look at profiles of other users but it would not be a bad feature at all.  In fact I should do it but it is not a priority.  

OK everything is looking good but the post admin page says images even though there is one image.  I think I should just say image for now.   OK.  I think things are looking good.  I need to think about whether the vew post page will be under the posts tab or the blog tab.  It's kind of confusing when somone goes to the blog and selects a post and then the post tab is selected.  I think the posts tab should be only for the user's posts.  Well I can worry about this later.  It's like spanish.  Sometimes you have some odd things that you need to remember.  It's not really important right now.  

OK.  Before adding the photos to comments mayb I should decide if I want to allow videos to be added to posts.  Honestly I need to start using policy bubble as well.  I need to write about my policy opinions about the news I see.  I need people find this information useful so the site can be popular.  For now my question is whether or not I should add videos to the posts or allow photos in the comments.  I think I should allow photos in the comments.  Allowing videos later on would be straigh forward later on since they are both files that have a preview image and a full version.  I need to make sure the other site feature are working before I expand what types of files are allowed.  The images are very useful for now.

So I am going to do the images for comments now.  Well it's getting late.  I made a lot of progress today.  When I get back I need to enhance the comment section.  It will be easier to link to the profile page now that I enhanced the profile page.  The tasks mentioned at the beginning of this section will be easier to do now.  So when I get back I will enhance the comment section.  After that I will be well on my way to having a well rounded site.  The visualization part will be interesting but I should probably start publishing useful policy things.  The usefulness of the site contesnt will really determine the success of policy bubble.  So once I have a well rounded site I can start publishing and that should determing the direction that the site could should go.  However I will still try to develope as much as I can.  Either way I need to go now.  I see the light at the end of the tunnel and I have lots of faith.

I might also want to paginate the comments. So how can I get started?  I need to make the comment section like the edit post section.  However this time it will not be a multipage form.  Alrhough I will still use the session conatiner.  I may even make the edit post page a single page form as well but that is not really impostant.  Actually I could make the comment form a multi page form if I wanted to.  So I just need to make the view post page like the edit post page.  I am going to start in the controller.

Do I want to use the step param?  That was so that the user would start over when they click the edit post button.  Is there a corresponding version of that for comments.  Basically there is.  When the user clicks the post to view it they should be brought back to the first page of the comment form.  Actually I think the add post is also a good template because it deals with naming collisions.  I can replace addStep with commentStep for example.  Also we are starting with a blank slate since there is not edit comment button yet.  Where am I going to store the comment photos?

Upload
        Post
                 1
                     temp
                         Image1.jpg
                         Image2.jpg
                    perm
                        Image1.png
                        Image2.png
                    comment
                         1
                             temp
                                 Image1.jpg
                                 Image2.jpg
                            perm
                                Image1.png
                                Image2.png
                    
Upload
        User
                 1
                 
I can still use the user folder and just delete what is there but once the form is saved I will need to get the comment number and create a directory with that number in a comment folder of the post.  I will need to think ahead and store it in a folder called perm because if a user wants to edit the post they will have a temp and a perm folder.  Also I will need to create the comment.own.edit permission.

It looks like I can reuse the addMaxFileValidator. Right now I am using the comment form but when I add the edit comment form I will need to create an edit cooment form.  That is opposite how I did posts.  I started with the edit post button using the post form.  Then I did the add post button using the add post form.  I will just need to remember that this is a bit different but it makes sense because I was starting from nothing and had to start with the basic names and than add to the names.  Also I will need to make an edit comment action.  I can put the comment id in the edit comment button.  I migh even have to show the post just to keep things consistent.  I may make a new view that shows the post just like the view post view.  

I think I should get the photos for each post in the view like I did for the blog and post admin page.  That is because I will have a loop of content and I will need to get the photos in each iteration.  I will need to relate posts to users now.  This currently not available but I can do it just like posts are related to users.  Perhaps I will need to create a lookup table.  Let me look at the ER diagram real quick just to verify. There is no lookup table.  I can just add the user_id field to a comment. However there is an author field.  I may not drop this right away.  However in the front end I may keep it blank.  In fact I don't need to have a user id to add photos to a comment but I will need it to edit a comment so I will continue.  

OK.  Now I am working on the view.  I need to loop through the comments and get the pictures like I did on the blog page.  However if it is step two.  I wonder if I should create a new comment button and start the comment form on a differnt page.  We will see.  I think I should make a wire frame.  I think it's a good idea to start the comment form on a new page because if you want to edit a comment it should take you there.  I don't think you should edit a comment on the same page.  So I think I should make an add comment action and an edit comment action.  Also a delete comment action.  Actually I think I should make a new controller.  A comment controller.  I might even make a comment admin page so a user can see all of their comments.  This is not something that facebook has.  Twitter has it.  

Since comments are starting to look like posts I wonder is I should have tags and status?  Maybe I should even just make posts be allowed to be posted to other posts.  I could say comment post or not.  Instead of having comments I could just let user put posts in other posts and even these posts could have comments.  I suppose I could just add another field to the post entity that is nullable.  It could be parent_id.  That means that this post is associated as a response to another post.  Do I need a child ID?  No because a post could have many responses.  I wonder how the responses would look like on the view post page.  Should it look like the post admin page or the blog page?  The benefit of the post admin page is that there are add and delete buttons.  However not all response posts will have this ability.  Only posts for which the user is the owner.  Since I plan do have a parent_id on the post admin page I could let a user search based on this.  If they see a post and want to edit or delete their response they could go to the post admin page and put in the ID.  So I am thinking of just displaying the response posts like the blog page does.  I should create a branch since I have already gone down the comment development path for quite a while.  I don't want to loose that work but I will need to roll back in order to implement this version.

It's time to go but I think I will get a second opinion on this.  I am at a crossroad and it's kind of weird to put other posts in the comment section.  As I have noted here it makes sense.  It is more like Twitter approach than a Facebook approach so I will need to think about it and talk about it.  Sometimes the planning is more impoartant than the programming so I am going to see what other people think.  To me it makes sense and I would do it if I didn't have to leave right now.  After all I have branches so I can try both approaches.  When I get back I am simply going to add the parent_id field to the post.  This can be used to make posts as roots, nodes or leaves in the policy bubble tree.  Policy tree might be better name for it.  Now the bubbles are taking a tree form.  It's more a policy graph where the nodes are bubbles.  It's like policy graph.  Or policy mesh.  Whatever.  I think its good that they will be linked together.  

So I am thinking about using posts instead of comments.  I will need to add the nullable parent_id field to the post entity. Or I could have a lookup table.  I think I should make a post_heirarchy table that has id, parent_post_id and child_post_id.  Ok.  I created the migration and ran it.  Now what should I do?  Looking at the view post page I would like to change 'add new comment' to 'add new response'. Also 'no comments' to 'no responses'.  'Leave Reply' to 'Leave Response'.  This should actually be a button that takes the user to an add post page similar to the one for a regular post.  I may even be able to reuse the same code.  We will see.  

So now I need to do the add response button.  I think this will be the same as the add post button.  So now I need to put the add post button in the Leave Response section of the view post page.  Right now I am trying to deal with how I can pass the id of the parent post to the post controller addPost action.  It is pretty straight forward.  Just add a quiery parameter.  However this is a two step form.  I need to store this variable until the second step.  Perhaps I can store it in the sessionc container.  

Instead of creating a new post manager function to add the post parent I could just expand the add new post function to take an optional parent post id.  So now I need to update the post view page to show the list of posts.  I need to remember that this list will be a mix of the blog list and post admin list.  It will be like the blog list but it will have buttons to edit or delete if it that user's post.  Also I would like to have the buttons for the post itself on the view post page if it the post of the user.  Also the view post action has some code that needs to be removed.  Also I will need to eventually remove the comment entity and code from the database.  Or I could leave it in as an added bonus.  Maybe sub posts will have comments but we'll see.  It just doesn't make sense to me right now.  

OK.  So now I am going to update the view post page to lists the posts like the blog does.  Can you image adding a response to a response?  Well that is going to happen.  I like it.  I am pretty tired so I am going to go home but I will continue with this after some rest.  I am just working on the pagination because I don't want a bunch of responses on the vew page.  Should I pass just the ID or should I pass the whole post to the findChildPosts.  How I am going to use the DQL in order to get the query for the paginator?  I need to find a good example.  

Well now that I can respond to posts using user posts I wonder what I should no next?  I am going to close out this task and start a new one for January now that it is January.
                                      

