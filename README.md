#### wp-plugin-tweet-onpublish

Lightweight plugin that simply tweets when a new article is published.

this is deliberarely lightweight and thus has some requirements:

1. oauth keys coded into the script
2. author needs twitterid meta data, or script needs to change to user nice_username or some such thing that coincides with the twitter handle
3. script needs the oauth library which you can get at github.com/kutf/twitter-oauth/
4. all 3 files then need to be copied into mu-plugins or plugins folder in a sub-folder in the later
5. it'll then work

the plugin doesn't user the options table to store keys or users and i don't care, I just wanted to replace the buggy wp-to-twitter plugin
and there was a distinct lack of options out there.
