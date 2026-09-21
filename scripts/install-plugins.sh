#!/bin/bash

# Add official plugins
plugins=(akismet contact-form-7 contact-form-7-honeypot cyr2lat query-monitor redirection svg-support)
for i in "${plugins[@]}"
do
  :
  wp plugin install $i --allow-root --activate
done

# Add vendor plugins
vendor_plugins=(advanced-custom-fields-pro.zip)
for i in "${vendor_plugins[@]}"
do
  :
  wp plugin install /var/www/html/vendor-plugins/$i --allow-root --activate
done
