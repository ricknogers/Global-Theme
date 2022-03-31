# prevent-direct-access-gold-3.0

Build status: [![CircleCI](https://circleci.com/bb/ymese_dev/prevent-direct-access-gold-3.0/tree/master.svg?style=svg&circle-token=cdb8e7e16511db7f9fda02a94c456fca364935e6)](https://circleci.com/bb/ymese_dev/prevent-direct-access-gold-3.0/tree/master)

**Getting started**

- Back-end

``` bash
./scripts/install-hooks.bash
composer update
```

- Front-end 

```bash
git submodule update --init
git submodule update --remote
cd client/pda-ui
npm install
npm run plan
npm run start
```

Enjoy!

**IIS Rewrite Rules**

```
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
  <system.webServer>
    <rewrite>
      <rules>
                <rule name="pda-original-link" patternSyntax="ECMAScript">
                    <match url="wp-content/uploads(/_pda/.*\.\w+)" />
                    <conditions logicalGrouping="MatchAll" trackAllCaptures="false">
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="/index.php?pda_v3_pf={R:1}" />
                </rule>
                <rule name="pda-private-link" patternSyntax="ECMAScript">
                    <match url="private/([a-zA-Z0-9-_]+)" />
                    <conditions logicalGrouping="MatchAll" trackAllCaptures="false">
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="/index.php?pda_v3_pf={R:1}&amp;pdav3_rexypo=ymerexy" />
                </rule>
                <rule name="wordpress" patternSyntax="Wildcard">
                    <match url="*" />
                    <conditions logicalGrouping="MatchAll" trackAllCaptures="false">
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php" />
                </rule>
      </rules>
    </rewrite>
  </system.webServer>
</configuration>
```

