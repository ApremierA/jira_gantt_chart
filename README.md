# Gantt Chart Board from JIRA

It can get data from JIRA via API with JQL query and show like the screenshot below.


![ScreenShot](https://raw.github.com/ApremierA/jira_gantt_chart/master/screen.jpg)

## Usage

Install Symfony dependencies with the following command. Also, you could download the composer executable on your own.

```bash
chmod +x ./composer.phar
./composer.phar install --ignore-platform-reqs
```

It will ask for MySQL Database parameters but there is no need to set it, continue with enter.

Next, open the app/config/parameters.yml file and add the following config parameters.

```bash
jira_host: https://your-domain.tld/jira
jira_user: JiraUserName
jira_api_key: Jira-API-Key-Or-Password-Might-Work-Too
```

Now you could run it with docker compose.

```bash
docker-compose up --build -d
```

It will listen on port 8082.

You can now access with URL: [http://localhost:8082](http://localhost:8082)


## Credits

### Based on Symfony

Welcome to the Symfony Standard Edition - a fully-functional Symfony
application that you can use as the skeleton for your new applications.

For details on how to download and get started with Symfony, see the
[Installation][1] chapter of the Symfony Documentation.
