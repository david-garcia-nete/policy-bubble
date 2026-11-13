[TOC]

# Post ZF3 book - completed #

I read Using Zend Framework 3 by Oleg Krivtsov.  Now I am ready to do this web site.  I already know what I want in the web site.  A bit of design would not hurt though.  What is it that I really want in the Web site?  I should make some CRC cards.  However the ZF3 book as some sophisticated features I could never design myself.  I really should put those in the web site and then adapt them.  That is exactly what I am going to do.  Also I am going to write unit tests.  So what should I do first?  That is the most difficult questions.  It depends on what the business plan is. Really what is the plan.  What have I been telling everyone?  

Firstly Policy Bubble mission is to improve policy making.  It does this by showing what the public's opinion is.  However opinion is not the only factor in good policy making.  Economics and ethics are important as well. Also federalism is important.  I really need to think about the marketing as well.  I could build an test a big blogging app for example but if no one uses it I am in the dust.  I need to think about how people are going to use it. 

Since I am a musician I really like to take a musician marketing approach.  That means treating this like a song.  Hell I might even create a theme song for this because I love music so much.  Anyway that is what I am going to do.  So when you write a song you typically need to plan it out well.  Also you need content.  Something like this.

Policy Bubble is a place you can go
there are many people going to a fro
they put in their beliefs
and with a sighs of relief
they realize their place in the know

When all is said and done
they can just have some fun
analyzing, fantasying, predicating
When the money adds up
and the feelings disrupt
they realize their place in the run

OK.  That was fun.  It just reiterates what I want the site to do.  I want people to put in there beliefs.  I also want it to be successful without people.  What If I can't get a bunch of people to use it?  It should still be a place of value.  I want it to be searchable as well.  Honestly I don't think I should worry too much or plan it out too much.  I should just do what ZF3 was meant to do.  That is anything.  I should get started and do something I know I will need.  I should put together the pieces from the ZF3 book.  I think that is I have users, roles, blogs, registration form, photo gallery..etc I will be on my way.  I can present the site to some policy people and say hey.  This is a place where you can put in your policy information and then analyze it.  You can put in ethics and economics and federalism.  You can put in opinion.  

Originally I wanted to have ratings.  That would be easy to add as well.  I need to think about the page organization though.  Will there be a home news feed like on Facebook?  Can anybody see the blog of a user or just that user?  I think it will be something like this.  One the home page it should provide some snapshot of what the site is doing.  For example the tag cloud can show which tags are most popular.  Honestly I have already planned all this.  I know what the fist steps are.  I already did this on policy bubble one.  I should update the layout.  Then I should add authentication, registration and unit tests.  Then I should add the blog and the photo gallery.  Since I know what my objective is I should just take it one step at a time.  

So I am going to take step one now.  First I am going to update the layout of the skeleton app.

# Update skeleton layout - complete #

I need to plan the skeleton home page.  Going from top down left to right I will need to update the ZF favicon to be the bubble.  The page title should say policy bubble.  The header icon should be a bubble.  Policy bubble should be there also.  Then it should say welcome to policy bubble.  This is a place where policy can be improved.  The other buttons and panels can be removed. The footer will need to be updated.  However I may keep the panels and buttons and provide similar functionalities. For example for the first button 'Fork Zend Framework on Github' I may say 'Fork Policy Bubble on Github'.  However the users of this site are not supposed to be developers.  I think I should draw what I want the home page to be like.  I will upload the drawings to google and put the links here.  
   
I think I should get rid of everything and start with text.  What would be the first text on the page?

Policy Bubble
This is a place where policy can be improved.

Public Opinion
Economics
Ethics
Federalism

This has the name ie Policy Bubble along with the tag line/objective.  Below it has some links.  I think this is great.  Is there anything else I would like to put in this web site?  Is there anything that interests me?  I just think this would be a small home page.  I think I should surf the web and look for stuff in order to fill this out a bit.  Let's start with public opinion.  I know that gallup and politico provide polls.  Maybe policy bubble could be hub with added features.  In econimics I could but some federal spending data.  In ethics I could put some news stories.  I federalism I could put some maps.  So it would look something like this.

Policy Bubble
This is a place where policy can be improved.

Public Opinion - Polls
Economics - Spending
Ethics - News
Federalism - Maps

I think that is fine.  However it's a bit boring and redundant.  Remember I want to start collecting data.  Not present other peoples data.  However I don't think I will be able to get a bunch of quality data right off the bat.  How can I start seeing results right now?  I don't want to reinvent the wheel.  Is there any web site that has data?  I think I am so exited that I read this ZF book that I haven't realized the implications.  Perhaps I should do some TDD.  

Test one.
Improve policy making.
How will I actually improve policy making.  Pubic opinion data does not directly improve policy making.  Let me come up with a mathematical proof.

Bad Policy- Unethical, expensive
make it ethical - make it politically correct, federalism
make it affordable - - make it politically correct, federalism
Good Policy- ethical, affordable

I think my problem is that I am forgetting the core of policy bubble - public opinion.  I should just retrace the steps of policy bubble one for the layout.  Then I am going to do the authentication.  That will be the tougher part.  I never got that right on policy bubble one because I never has unit tests.  I also never had good registration.  I should implement those next.  Then I should go straight to the blog.  However I need to think about the navigation.  I need to think about the use cases. I need to think about all the OOP design.  I am going to start with the requirements, use cases, then crc, then uml, then sequence, then state.  These will be basic classes.  Other classes will emerge in the future.  This is just fleshing out the design a but more.  Since I am already in the requirements section of the wiki I can starts to talk about use cases.  The user will go to the web site.  They will say hey.  This is policy bubble.  This is a place where I can get my political thoughts out and get some immediate feed back.  So they go to the web site ready to vent.  They type in policy bubble.com in the address bar and they are there.  Then they say.  I am interested in talking about education.  I think college should be free.  Or think it should not be free or whatever.  By the way I don't know why anyone would not want college to be free.  Anyway, they go there and say that college should be free.  What would they see?  Something like create an issue.  

I finished they layout.  I just needed a sleep on it.  It looks really good.  I am going to start implementing the registration process.

# Registration - completed #

Basically all of the code is in the ZF3 book.  I think I should implement the users functionality and then do the registration.  The user functionality allows the admin to create users.  Once that is in place, adding the registration code is straight forward.  I am going to go through the user chapter step by step and create unit tests as well. 

I am trying to add too much at once.  I tried adding the user module but now I am just bringing in everything.  I just copied the user module from the ZF3 book.   I had to update my composer.json and modules.config.php.  Then I updated global.php.  Now I am configuring the Application module and realized that Oleg has it different in the user demo.  I feel like I am in a deep hole again.  Although I like what Oleg does I cannot just through in everything at once without rhyme or reason.  Perhaps I should create a branch and then backup.  OK.  I created a branch called user-module.  Then I went back to master and rolled back.  What should I do now?  OK I switched back to the user_module branch.  It says A plugin by the name "mainMenu" was not found in the plugin manager Zend\View\HelperPluginManager.  #2 C:\Apache24\htdocs\policybubble2\module\User\view\user\auth\login.phtml(4): Zend\View\Renderer\PhpRenderer->__call('mainMenu', Array)

It looks like the login page wants the mainMenu view helper.  What I should really do is just user Olegs role sample as the base for my site.  However I wanted to go step by step to do unit tests.  What should I do?  Perhaps I can create a new repo and apply this layout of policy bubble.  That is what I am going to do.  I can always jump back here.  Perhaps I can just cut this part out of the code.  Since I am not using the breadcrumbs I don't need the menu helper.  I can always added it it in later.  OK.  That works.  However, now I am getting redirected to the login page when I go to the home page.  I don't want that to happen.  

Why is it redirecting?  Perhaps because I have not configured the access filter in the Application module.  I will do that now. 

I'm thinking I should use the nav manager, bread crumbs, and menu functionality of Oleg's sample without messing up my layout.  The reason is because it I am trying to set up the login button to go to the login page.  However I want to use the uri helper not the relative path.  Also I want the menu item to highlight.  Yes.  I really should apply all the menu functionality Oleg has created to my menu.  I will do that next.  However I should download the user demo so I can compare apples to apples.  I keep looking at the role demo and I am not that far yet.  I will get there though.

I am going to save my progress and the update the menu.  This is really the navigation.  

## Navigation - completed ##

OK.  I installed the userdemo sample and I see what my goal is.  I need to put the sign in option on the menu bar.  However I am not going to put it all the way on the right.  Also I am going to put the breadcrumbs below the menu.  Also the menu has a nav manager.  When the user logs in the menu items change.  I am going to incorporate this as well.  

I am going to go to the chapter where this happens.  That is chapter six.  I am looking at the code for the layout that Oleg wants us to put.  He says to replace the code between the body tag with his code.  Since I like the ZF nav bar that goes all the way across the page at the top I am not going to do the container>row>nav he does.  I am just going to do nav as is.  Next he has another row for the bread crumbs.  This creates a domino effect that make it hard for me to follow his format.  He puts the content in a row while ZF3 puts it in another container.  ZF puts rows in the templates.  Does Oleg do this?  He does.  I suppose it's ok to put rows within rows.  So I am going to put the breadcrumbs in the second container.  The breadcrumbs look good.  I just don't understand why they are not in the login and settings page.  I should not worry about this now.  I will come back to this when I have the pages working.

OK.  6.9 explains this.  The layout will call the view helper and get these items.  So I am going to move the navbar and breadcrumb code into the view helpers. If I want these items in a page I can add items to them in the view.  Basically I will always need to add the active page but the breadcrumbs are optional.  Sweet!

I just copying the code from the userdemo and I see the menu factory but I am not seeing it in the chapter.  OK.  He mentions in a comment that he extended it with the NavManager to change the items based on whether the user is logged in or not.  I will use that code.  I just need to modify it to make sure it is appropriate for my needs.

So now I am going to copy all the code over and modify it.  

Everything seems to be working fine.  The only problem is when I click sign in after I am already logged in I get an error message that was thrown.  Is this the intention of Oleg?  Yes.  That was the intention of Oleg.  He did not have a sign in link in view if the user is signed in like I do.  However the user could still go to the /login url.  By the way.  I am going to say sign in in the new and log in in the code.  Anyway I need to think about how I am going to handle the sign in button.  Perhaps I should just remove it.  Or perhaps Yes.  That is what I will do for now.  OK so the navigation is done. 

## Roles - completed ##
Before I can address registration I need to address roles.  That is because all logged in users can see the admin pages which is not good.  I need to at least distinguish between admins and non admins.  That is exactly what chapter 16 does.  It also lets me make other roles if I want.  That is ok.  So I am going to do implement the roles now.

By the way.  After that is done I will need to implement the registration which will just let a user add themselves to the user database.  That should be simple.  Then I will need to implement the blog, photo gallery and feedback form.  I think that these will behave differently based on whether the user is logged in or not.  Or perhaps they should be based on the user like in Facebook.  In Facebook you must create an account to use it.  Perhaps I will do that here.  However I don't really want to do friends and stuff.  That is what Facebook is for.  This site will be so that policy can be improved.  The blog should allow the user to say what they think.  I am going to take it one step at a time. First the blog will just let the user create and share what they are interested in.  There will likely be a networking component.  I will cross that bridge when I get there.  OK.  I am going to focus on roles.

Roles are in chapter 17.  Let me jump there now.  Ok.  I finished adding the roles.  This was easy because I had already added users and navigation.

# Registration form - completed #
Now I can work on the registration form.  So how am I going to do the registration.  I think I should put the link on the sign in form.  How does it look on the form demo?  I don't want to make the registration button very small.  Most sites say 'Not a member?  <Register here>.'  in small letters under the sign in form.  People who are not extremely computer literate will not understand.  It would be better to have a very large registration button on the home page.  I can use the view helper to hide these if the user is logged in.  So I am going to add those register and sign in buttons back to the index page.  Actually I am going to go with the common case.  I am going to put Olegs user registration link under the sign in form.  Later I can make it more visible.  I doing this because the sign in link already hides when the user is signed in and I don't want to duplicate existing functionality.  I'm not sure I want to collect so much data during the registration process though.  Some people don't want to enter their address.  Also I am not going to collect credit card info because it is going to be free to use.  Money could be made by sponsors.  I would like to collect more info about the user later on for data visualization purposes.  Data visualization is going to be an important part of the site.  However perhaps I should make the location of the user not permanent.  That is that the location can be tagged in individual posts.  Anyway I want registration to be simple.  So I am just going to do a one page form using the first page of the registration form example.  I can still use the code.  I am just going to cut out the multi step part. 

I am implementing the registration form and I was planning on just doing the first page.  However if I keep the whole thing it would be easier for me.  The question is whether I think it is a good idea to ask for a credit card?  What would be the advantage of doing that.  Well it would help the business have money which is good for me and the business.  What would the users be paying for?  They would be paying for more developers, more services etcetera. Maybe I can allow free users to read blogs and payed users to write blogs.  I think I should do it merely as an exercise.  I can always remove it later. 

So I am going to use the full form.  I am going to require users to pay to use the site.  However if they are going to pay they probably don't want it to be open source.  Then the whole world would know where their credit card data is stored.  Well I think it would be OK.  As long as I have secure measures in place I should be good.  One suggestion I received was to require a credit card payment to be a member.  I think I should make the code private so security for the users.  A benefit of requiring payment for the user is that would have a better experience because they would not get a bunch of trash to read.  If you read something you would know that someone has payed to put their opinion there.  I just don't think the site would be very popular if people had to pay.  I want this to be a big data site.  The money will come in later.  So I am going to do just the first page of the registration form.

Well actually I am going to use the full form and keep it open source.  I am just developing right now.  Well I added mostly everything and I see there are some extra configurations related to payment but I don't see them in the book.  Also I see the mail sender.  Ah.  Those are from the validation group and contact form examples.  I don't need to worry about them.  Well I have all the code for the registration form from the book.  I just need to add the link from the view.  

Now the error messages are not red.  I remember from the book that Oleg mentioned this but I can't find it.  Got it.  I had to update style.css.  Well I finished added the registration form but but is not fully functional.  Now I am going to begin the process of of updating it to make it actually work.  Well I think I'm going to just do the first part of the form and make it free to register.  However I think I will do a capcha.  Or should I make it paid?  Well it is going to make it hard for people to use it is paid.  Perhaps I can start making it free now and later as I add more functionality I can add a paid version.  Or I can add advertisements or google add sense.  I think Facebook as a sensible sign up process.  When you log out the home page is just a sign up and login form.  I may evolve my site to be like this.  For now I am just doing the simplest things first.  

So I am just going to require name, password and email.

I captured screen shots of the Facebook registration process as a reference. *(Link to a private Google Drive file removed.)*
After you fill out the signup form on the home page you will see the screens and emails captured in those screenshots.  The confirmation email is a standard sign up feature. There is some code in the ZF3 book for a confirmation email.  It's for updating your password.  I can adapt it and just use it as a confirmation email that makes the user active.  Next when the user has logged in the question is what will happen.  Facebook has a walk though process to help the user add and interact with friends.  Since friends is not the core of Policy Bubble I could provide a walk though process to help the user add and interact with policies.  Basically it will be Facebook but instead of friends it will be policies or platforms.  So each user will have a policy profile like a user profile in Facebook.  They will add other policies that they like.  However I think this is copying too much.  Perhaps They will create blogs.  Each one will essentially evolve into a policy as I develop more. So users can create policies and tag other policies.  This is more like Twitter.  Anyway they can tag their policy and search other polices that have the same tags.  They can see which policies are the most popular using filters.  That is the core of policy bubble.  So with respect to registration when user register they would just be allowed to blog.  They would be taken to the blog interface.  If they are not logged in they can see all blogs.  Of course there will need to be user polices.  People cannot post inappropriate content but that is neither here not there right now. So I will have two pages.  One is one shows the blogs of one user.  The other shows the blogs of everyone.  The route name for the global blog would not have the user id it it.  Now of course logged in users can see the global blog.  What about photos.  I think they should be the same.  There should be a global photo gallery and a user photo gallery.  What about the feedback form.  Perhaps the feedback form will just be global.  I can send a confirmation to the user as well.  It's nice for the user to know what they sent.  

Perhaps I should let the user go to the home page to see the global feeds.  There is already a user page where I can put links to the user feeds.  I pretty much have planned out the core functionality of policy bubble. When I am done implementing this it will already provide quite a bit of functionality.  I may begin automated tests in order to prepare for added functionality. Then I may do the copyright and first release.  Or I may add a but for functionality after some user testing before releasing it.  At either rate I am getting exited about putting this out live.  

So right now I need to finish the registration form. By the way I also plan to make the site available in Spanish as well.  Facebook has a language selector in the footer of the home page for logged out users.  This defaults to to English for me.  I think that is based of the browsers setting.  Once the user is logged in they can change their language in their settings page.  For me I think I should do it similarly.   I should but a language selector in the footer on the home page.  When the user is logged in the can change the language in their settings.  It will default to the browser language. I could also put it in the menu bar.  The details are not important now.  I like the footer idea more now.

OK. The registration form.  Well now that I have the deign of my site fleshed out I think I should bring up money one more time.  I don't really want to put adds on the site.  That is kind of ugly.  However Facebook does it pretty well. I should not worry about this.  OK.  The registration form.  I need to trim it down to one page. I am going to continue this on the December wiki page.  
