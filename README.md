# GitHubImage

Single file index.php.

Call it with the following GET parameters
- `owner` : github owner name
- `repo` : github repository name
- `field` : github field name ( for a list of fields see  for example : https://api.github.com/repos/thipages/GitHubImage )
- `textColor` : text color in hex (default : #ffffff)
- `bgColor` : background color in hex (default : #000000)

If one of the parameter `owner` or `repo` or `field` is incorrect, the image will contain "error" as text

# Usage
eg in a readme file

```html
<img src="mysite.com/index.php?owner=thipages&repo=GitHubImage&field=description"/>
<img src="mysite.com/index.php?owner=thipages&repo=GitHubImage&field=created_at"/>
```
