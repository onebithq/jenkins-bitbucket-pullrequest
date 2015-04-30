# Jenkins Bitbucket Pull Request
Scripts to receive and update Bitbucket pull request for Jenkins integration.

## Requirements

* Jenkins plugin
	* [Build Token Root](https://wiki.jenkins-ci.org/display/JENKINS/Build+Token+Root+Plugin)
	* [Embedable Build Status](https://wiki.jenkins-ci.org/display/JENKINS/Embeddable+Build+Status+Plugin)

## Setup

1. Upload the script to your web server (let's say it's `http://example.com`) 
2. Pull the dependencies using `php composer install`
3. Create the configuration in `configs` directory, please refers to the included `sample.json` file.  
4. There are 2 main scripts: 
	-  `http://example.com/hook.php`: Triggered by Bitbucket hook to start the Jenkins Build
	-  `http://example.com/pull-request.php`: Triggered by Jenkins post-build task to comment the build status to the pull request.
5. Open your bitbucket repository hooks settings and add a **Pull Request POST** hook pointing to your hook URL (e.g. `http://example.com/hook.php?config=sample`, **sample** is your configuration file name)
6. Make sure your Jenkins installation has the required plugins:
	1. [Build Token Root](https://wiki.jenkins-ci.org/display/JENKINS/Build+Token+Root+Plugin)
	2. [Embedable Build Status](https://wiki.jenkins-ci.org/display/JENKINS/Embeddable+Build+Status+Plugin)
7. In the Jenkins Job that responds to pull requests, create the following configurations:
	1. String parameters: `PULL_REQUEST_ID`, `PULL_REQUEST_TITLE`, `SOURCE_COMMIT_SHA`
	2. Build triggers: **Trigger builds remotely** 
	3. Add build steps as needed (e.g. unit testing)
	4. Last build step: **Execute shell**

		```
# create a comment on the pull request
curl -XPOST "http://example.com/pull-request.php?pull_request_id=$PULL_REQUEST_ID&status=pass&config=sample&build_number=$BUILD_NUMBER"
		```
8. The comment message template is still hardcoded in `pull-request.php` (WIP)  
		
## Future works

* Turn this project into Jenkins plugin