# TriggerBundle [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://github.com/cleentfaar/CLTriggerBundle/tree/master/LICENSE.md)

A Symfony bundle that let's you execute handlers on incoming GET parameters; optionally letting you redirect
the client back to an URL with certain parameters stripped.

[![Build Status](https://img.shields.io/travis/cleentfaar/CLTriggerBundle/master.svg?style=flat-square)](https://travis-ci.org/cleentfaar/CLTriggerBundle)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/cleentfaar/CLTriggerBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/cleentfaar/CLTriggerBundle/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/cleentfaar/CLTriggerBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/cleentfaar/CLTriggerBundle)
[![Latest Version](https://img.shields.io/github/release/cleentfaar/CLTriggerBundle.svg?style=flat-square)](https://github.com/cleentfaar/CLTriggerBundle/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/cleentfaar/trigger-bundle.svg?style=flat-square)](https://packagist.org/packages/cleentfaar/trigger-bundle)


### Documentation

- [Installation](Resources/doc/installation.md)
- [Usage](Resources/doc/usage.md)


### Why not just create my own request listeners, and do what I want from there?

You can of course still do this, and in some specific situations you might need that level of control.

In my projects however, I've noticed that often I just need to execute some business logic and then redirect
the user back to the url without that parameter.

Furthermore, the intend of this bundle is to make it easy to have multiple services handle the same request
and apply their own logic, instead of making multiple request-listeners with all kinds of different scenarios, each
hooking into every request coming through your application! It also helps repeating code to check whether the request is
using the GET-method, your parameter is available in the query string, etc.
