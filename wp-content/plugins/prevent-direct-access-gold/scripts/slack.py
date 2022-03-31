from commands import *
import requests
import re

def getCommitsHistory():
    tags = getstatusoutput('git tag --sort=-creatordate')[1].split('\n')
    reg = re.compile(r"(^\d+\.\d+\.\d+(\.\d+)*$)")
    tags = list(filter(reg.search, tags))
    endTag = tag[0]
    beginTag = tag[1]
    print '%s upgrade to %s' %(beginTag, endTag)
    query_str = 'git log %s..%s' %(beginTag, endTag)
    print query_str
    return getstatusoutput('git log %s..%s --merges --first-parent integration' %(beginTag, endTag))[1].split('\ncommit ')

def getFeaturesCreated():
    commits = getCommitsHistory()
    reg = re.compile(r"feature|WD")
    newCommits = list(filter(reg.search, commits))
    print newCommits
    features = []
    for commit in newCommits:
        try:
            found = re.search('(feature|WD).+', commit).group(0)
            features.append(found)
        except AttributeError:
            print AttributeError
    return list(set(features));

def sendMessageToSlack():
    currentBranch = getstatusoutput('git rev-parse --abbrev-ref HEAD')[1]
    if ( currentBranch == 'master' ):
        featureCreated = getFeaturesCreated()
        if (len(featureCreated) == 0):
            print "Zero"
            return 0
        url = "https://hooks.slack.com/services/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"
        payload = """
            {
                \"text\": \"Bump, Say hi to <!everyone>\",
                \"attachments\": [
                    {
                        \"title\": \"Wow, The god was born :stuck_out_tongue_winking_eye:\",
                        \"fields\": [
                        ],
                        \"author_name\": \"PDA Gold\",
                        \"author_icon\": \"https://preventdirectaccess.com/wp-content/uploads/2018/04/pda-logo-icons.png\",
                        \"image_url\": \"https://media3.giphy.com/media/q8pghZNgAQ0rm/giphy-downsized.gif?cid=6104955e5bffa601486d387855203072\"
                    },
                    {
                        \"title\": \"Feature updated\",
                        \"text\": \"%s\"
                    }
                ]
            }
            """ %("\n".join(featureCreated))
        headers = {
            'Content-Type': "application/json",
            }
#        response = requests.request("POST", url, data=payload, headers=headers)
        print(featureCreated)
#        print(response)
    else:
        print 'Not master branch'
sendMessageToSlack()
