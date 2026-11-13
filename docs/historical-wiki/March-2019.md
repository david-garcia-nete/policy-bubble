*(Editorial note: the products/services shop described in this entry was planned but never implemented.)*

I need to build a products/services module on PB.  I need to offer products/services like server, database, Web administration.  Maybe I can freelance that someone builds me this for policy bubble.  Or I could do it myself.  I really think I'm going to build this shop.

Before I do this I think it would be a good idea to think about what I'm going to put in the tables. eg
Brands
Categories
Products

I mean I really need to think about this.

I was thinking I could make business digitization one of my products.  I could charge per hour.  What about my categories and brands?  So for my categories I can have the things I'm interested in like aviation, music, sports etc.  For the brands I can either make my own brand or sell a real brand.  I could partner with colleagues and help them sell for a commission. Really it would be a marketplace for all.  Just like with your blog you need to start small.  

So this sounds great.  I just need to set up development on my local computer and get started!

I did composer install and got some suggestions to install php modules.  If I run into problems on the site this list may help.

aws/aws-sdk-php suggests installing aws/aws-php-sns-message-validator (To validate incoming SNS notifications)
beberlei/DoctrineExtensions suggests installing nesbot/carbon (Alternative to DateTime)
beberlei/DoctrineExtensions suggests installing zf1/zend-date (Alternative to DateTime)
zendframework/zend-view suggests installing zendframework/zend-feed (Zend\Feed component)
zendframework/zend-view suggests installing zendframework/zend-navigation (Zend\Navigation component)
zendframework/zend-view suggests installing zendframework/zend-permissions-acl (Zend\Permissions\Acl component)
zendframework/zend-mvc suggests installing http-interop/http-middleware (^0.4.1 to be used together with zend-stratigility)
zendframework/zend-mvc suggests installing zendframework/zend-log (^2.9.1  To provide log functionality via LogFilterManager, LogFormatterManager, and LogProcessorManager)
zendframework/zend-mvc suggests installing zendframework/zend-mvc-console (zend-mvc-console provides the ability to expose zend-mvc as a console application)
zendframework/zend-mvc suggests installing zendframework/zend-psr7bridge ((^0.2) To consume PSR-7 middleware within the MVC workflow)
zendframework/zend-mvc suggests installing zendframework/zend-servicemanager-di (zend-servicemanager-di provides utilities for integrating zend-di and zend-servicemanager in your zend-mvc application)
zendframework/zend-mvc suggests installing zendframework/zend-stratigility (zend-stratigility is required to use middleware pipes in the MiddlewareListener)
symfony/console suggests installing symfony/event-dispatcher
symfony/console suggests installing symfony/lock
symfony/console suggests installing symfony/process
doctrine/cache suggests installing alcaeus/mongo-php-adapter (Required to use legacy MongoDB driver)
zendframework/zend-form suggests installing zendframework/zendservice-recaptcha (in order to use the ReCaptcha form element)
zendframework/zend-cache suggests installing ext-apc (APC or compatible extension, to use the APC storage adapter)
zendframework/zend-cache suggests installing ext-apcu (APCU >= 5.1.0, to use the APCu storage adapter)
zendframework/zend-cache suggests installing ext-dba (DBA, to use the DBA storage adapter)
zendframework/zend-cache suggests installing ext-memcache (Memcache >= 2.0.0 to use the Memcache storage adapter)
zendframework/zend-cache suggests installing ext-memcached (Memcached >= 1.0.0 to use the Memcached storage adapter)
zendframework/zend-cache suggests installing ext-mongo (Mongo, to use MongoDb storage adapter)
zendframework/zend-cache suggests installing ext-redis (Redis, to use Redis storage adapter)
zendframework/zend-cache suggests installing ext-wincache (WinCache, to use the WinCache storage adapter)
zendframework/zend-cache suggests installing ext-xcache (XCache, to use the XCache storage adapter)
zendframework/zend-cache suggests installing mongofill/mongofill (Alternative to ext-mongo - a pure PHP implementation designed as a drop in replacement)
zendframework/zend-authentication suggests installing zendframework/zend-ldap (Zend\Ldap component)
doctrine/doctrine-module suggests installing doctrine/data-fixtures (Data Fixtures if you want to generate test data or bootstrap data for your deployments)
doctrine/doctrine-module suggests installing zendframework/zend-mvc-console (^1.1.10 if you are using ZF3)
ocramius/proxy-manager suggests installing ocramius/generated-hydrator (To have very fast object to array to object conversion for ghost objects)
ocramius/proxy-manager suggests installing zendframework/zend-soap (To have the Soap adapter (Remote Object feature))
ocramius/proxy-manager suggests installing zendframework/zend-xmlrpc (To have the XmlRpc adapter (Remote Object feature))
doctrine/migrations suggests installing jdorn/sql-formatter (Allows to generate formatted SQL with the diff command.)
paragonie/random_compat suggests installing ext-libsodium (Provides a modern crypto API that can be used to generate random bytes.)
zendframework/zend-math suggests installing ext-bcmath (If using the bcmath functionality)
zendframework/zend-math suggests installing ext-gmp (If using the gmp functionality)
zendframework/zend-captcha suggests installing zendframework/zend-text (Zend\Text component)
zendframework/zend-captcha suggests installing zendframework/zendservice-recaptcha (ZendService\ReCaptcha component)
zendframework/zend-mail suggests installing ext-intl (Handle IDN in AddressList hostnames)
zendframework/zend-i18n suggests installing ext-intl (Required for most features of Zend\I18n; included in default builds of PHP)
zendframework/zend-session suggests installing mongodb/mongodb (If you want to use the MongoDB session save handler)
zendframework/zend-json suggests installing zendframework/zend-json-server (For implementing JSON-RPC servers)
zendframework/zend-json suggests installing zendframework/zend-xml2json (For converting XML documents to JSON)
phpunit/phpunit-mock-objects suggests installing ext-soap (*)
sebastian/global-state suggests installing ext-uopz (*)
zendframework/zend-debug suggests installing ext/xdebug (XDebug, for better backtrace output)
zendframework/zend-developer-tools suggests installing aist/aist-git-tools (Show you informations about current GIT repository)
zendframework/zend-developer-tools suggests installing bjyoungblood/bjy-profiler (Version: dev-master, allows the usage of the (Zend) Db collector.)
zendframework/zend-developer-tools suggests installing jhuet/zdt-logger-module (Show you log data from Zend\Log)
zendframework/zend-developer-tools suggests installing ocramius/ocra-service-manager (OcraServiceManager can help you track dependencies within your application.)
zendframework/zend-developer-tools suggests installing san/san-session-toolbar (SanSessionToolbar can help you see current Zend\Session data you're using within your application.)
zendframework/zend-developer-tools suggests installing snapshotpl/zf-snap-event-debugger (ZfSnapEventDebugger can help you debug events from Zend\EventManager)
phpunit/phpunit suggests installing phpunit/php-invoker (^1.1)
zendframework/zend-test suggests installing zendframework/zend-mvc-console (^1.1.8, to test MVC <-> console integration)

By the way.  I'm thinking I should treat the store like the blog.  All users should be able to sell, not just me.  Yes, I may be the only one that sells at first but I want to have the infrastructure to make it a collaborative area.  However I would need to make multiple store fronts.  Well not really.  

However what would make this collaborative store better than others?  Well it's based on policy?  That's weird.  How can you enforce that?  I could attach products to blog posts.  That way they can say whether this is something they can sell. 

Also I wouldn't have to create a bunch of product, category and brand tables and MVC components.  The products can be part of the posts.  The categories part of the tags.  The brand is the user.  I would just need to track orders and payments and create a cart object.   

I think the idea of blogging an selling at the same time is weird though.  Can I give an example.  What if I blog and say this blog post is about a service I offer.  Here is the price.  

Well it's better than what I had.  I had people paying in order to post.  How about reversing that and having people get paid to post.  It's just really hard to keep inventory.  If I'm the only one selling I can do that better.  Well guess users would just need to keep inventory.  

So an example of a post that I could sell would be a post about database administrations.  I could say hey this is just a service I have.  Here is my price.  Or I could say hey, this is a product I think would really help to improve my policy interests.  For example I could say hey.  I would create a web site for you for $140/hour.  If you would like this service just click buy now.  

This presents some problems.   First it's hard for people to find products.  Some in your steam are just blog posts and others are products.  I could put a product only filter on the blog.  

That way people could not only see public opinion, they could buy it.  I just need to think about the work flow.  So everyone has a cart.  Even if your not signed in.  

But don't you think it's weird that people could respond to products with their own?  Not really.  That's actually a good thing.

So your just improving how people interact with blog posts.  Or I could just skip all this and start blogging about the products and services I offer.  If I just keep people updated on my prices and availability then we are golden.  No need to create more site complexity.  Let me just try this.  


