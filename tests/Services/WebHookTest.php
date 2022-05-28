<?php

use RotHub\PHP\Facades\WebHook;
use RotHub\PHP\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class WebHookTest extends TestCase
{
    /**
     * @test
     */
    public function testGitHub()
    {
        $ok = WebHook::fake($this->config())
            ->setRequest($this->github())
            ->checkSign();

        $this->assertEquals($ok, true);
    }

    /**
     * @test
     */
    public function testGitee()
    {
        $ok = WebHook::fake()
            ->setRequest($this->gitee())
            ->checkSign();

        $this->assertEquals($ok, true);
    }

    protected function github()
    {
        $headers = '{"Connection":"close","Content-Type":"application\/json","X-Hub-Signature-256":"sha256=2deb8173b6185a730cc8fa41fbc0f3fa976b59e79117dc7e32126f2972fc9239","X-Hub-Signature":"sha1=ed6335bd68ef13829c34d21e249d011c02ff1045","X-Github-Hook-Installation-Target-Type":"organization","X-Github-Hook-Installation-Target-Id":"101108497","X-Github-Hook-Id":"346794341","X-Github-Event":"ping","X-Github-Delivery":"c627fc10-9e0f-11ec-8eca-c44f41fb12a5","Accept":"*\/*","Content-Length":"1996","User-Agent":"GitHub-Hookshot\/4d7fd9c","Host":"webhook.rothub.com"}';
        $content = '{"zen":"Keep it logically awesome.","hook_id":346794341,"hook":{"type":"Organization","id":346794341,"name":"web","active":true,"events":["push"],"config":{"content_type":"json","insecure_ssl":"0","secret":"********","url":"http://webhook.rothub.com"},"updated_at":"2022-03-07T12:11:52Z","created_at":"2022-03-07T12:11:52Z","url":"https://api.github.com/orgs/useio/hooks/346794341","ping_url":"https://api.github.com/orgs/useio/hooks/346794341/pings","deliveries_url":"https://api.github.com/orgs/useio/hooks/346794341/deliveries"},"organization":{"login":"useio","id":101108497,"node_id":"O_kgDOBgbLEQ","url":"https://api.github.com/orgs/useio","repos_url":"https://api.github.com/orgs/useio/repos","events_url":"https://api.github.com/orgs/useio/events","hooks_url":"https://api.github.com/orgs/useio/hooks","issues_url":"https://api.github.com/orgs/useio/issues","members_url":"https://api.github.com/orgs/useio/members{/member}","public_members_url":"https://api.github.com/orgs/useio/public_members{/member}","avatar_url":"https://avatars.githubusercontent.com/u/101108497?v=4","description":null},"sender":{"login":"sxfmao","id":32216262,"node_id":"MDQ6VXNlcjMyMjE2MjYy","avatar_url":"https://avatars.githubusercontent.com/u/32216262?v=4","gravatar_id":"","url":"https://api.github.com/users/sxfmao","html_url":"https://github.com/sxfmao","followers_url":"https://api.github.com/users/sxfmao/followers","following_url":"https://api.github.com/users/sxfmao/following{/other_user}","gists_url":"https://api.github.com/users/sxfmao/gists{/gist_id}","starred_url":"https://api.github.com/users/sxfmao/starred{/owner}{/repo}","subscriptions_url":"https://api.github.com/users/sxfmao/subscriptions","organizations_url":"https://api.github.com/users/sxfmao/orgs","repos_url":"https://api.github.com/users/sxfmao/repos","events_url":"https://api.github.com/users/sxfmao/events{/privacy}","received_events_url":"https://api.github.com/users/sxfmao/received_events","type":"User","site_admin":false}}';

        $request = Request::create('', 'POST', [], [], [], [], $content);
        $request->headers->replace(json_decode($headers, true));
        return $request;
    }

    protected function gitee()
    {
        $headers = '{"Host":"webhook.rothub.com","Accept-Encoding":"gzip;q=1.0,deflate;q=0.6,identity;q=0.3","Content-Length":"5391","X-Git-Oschina-Event":"Push Hook","X-Gitee-Event":"Push Hook","X-Gitee-Ping":"true","X-Gitee-Timestamp":"1646657144174","X-Gitee-Token":"mNR0yK7W4UlZ4E4aehEVtupxLaE73siwbGgDVSloTVE=","Content-Type":"application\/json","User-Agent":"git-oschina-hook","Accept":"*\/*"}';
        $content = '{"ref":"refs/heads/test_version","before":"3a6902040b2fd1e240315a84611d36eef14b4f2f","after":"ad2f7b1729eea675cd44da48f5e53abdf8f242a8","created":false,"deleted":false,"compare":"https://gitee.com/oschina/gitee/compare/3a6902040b2fd1e240315a84611d36eef14b4f2f...ad2f7b1729eea675cd44da48f5e53abdf8f242a8","commits":[{"id":"3a6902040b2fd1e240315a84611d36eef14b4f2f","tree_id":"ad2f7b1729eea675cd44da48f5e53abdf8f242a8","parent_ids":["ad2f7b1729eea675cd44da48f5e53abdf8f242a8"],"distinct":true,"message":"这是一条测试 Push 类型 WebHook 触发的推送","timestamp":"2020-04-15T21:09:40+08:00","url":"https://gitee.com/oschina/gitee/commit/3a6902040b2fd1e240315a84611d36eef14b4f2f","author":{"time":"2020-04-15T21:09:40+08:00","id":1,"name":"Gitee","email":"gitee@gitee.com","username":"oschina-org","user_name":"oschina-org","url":"https://gitee.com/oschina-org"},"committer":{"id":1,"name":"Gitee","email":"gitee@gitee.com","username":"oschina-org","user_name":"oschina-org","url":"https://gitee.com/oschina-org"},"added":null,"removed":null,"modified":["README.md"]}],"head_commit":{"id":"3a6902040b2fd1e240315a84611d36eef14b4f2f","tree_id":"ad2f7b1729eea675cd44da48f5e53abdf8f242a8","parent_ids":["ad2f7b1729eea675cd44da48f5e53abdf8f242a8"],"distinct":true,"message":"这是一条测试 Push 类型 WebHook 触发的推送","timestamp":"2020-04-15T21:09:40+08:00","url":"https://gitee.com/oschina/gitee/commit/3a6902040b2fd1e240315a84611d36eef14b4f2f","author":{"time":"2020-04-15T21:09:40+08:00","id":1,"name":"Gitee","email":"gitee@gitee.com","username":"oschina-org","user_name":"oschina-org","url":"https://gitee.com/oschina-org"},"committer":{"id":1,"name":"Gitee","email":"gitee@gitee.com","username":"oschina-org","user_name":"oschina-org","url":"https://gitee.com/oschina-org"},"added":null,"removed":null,"modified":["README.md"]},"total_commits_count":1,"commits_more_than_ten":false,"repository":{"id":151,"name":"Gitee FeedBack","path":"git-osc","full_name":"oschina/git-osc","owner":{"login":"oschina-org","avatar_url":"https://gitee.com/assets/favicon.ico","html_url":"https://gitee.com/oschina-org","type":"User","site_admin":false,"id":1,"name":"Gitee","email":"gitee@gitee.com","username":"oschina-org","user_name":"oschina-org","url":"https://gitee.com/oschina-org"},"private":false,"html_url":"https://gitee.com/oschina/git-osc","url":"https://gitee.com/oschina/git-osc","description":"","fork":false,"created_at":"2020-04-15T21:09:40+08:00","updated_at":"2020-04-15T21:09:40+08:00","pushed_at":"2020-04-15T21:09:40+08:00","git_url":"git://gitee.com:oschina/git-osc.git","ssh_url":"git@gitee.com:oschina/git-osc.git","clone_url":"https://gitee.com/oschina/git-osc.git","svn_url":"svn://gitee.com/oschina/git-osc","git_http_url":"https://gitee.com/oschina/git-osc.git","git_ssh_url":"git@gitee.com:oschina/git-osc.git","git_svn_url":"svn://gitee.com/oschina/git-osc","homepage":null,"stargazers_count":11,"watchers_count":12,"forks_count":0,"language":"ruby","has_issues":true,"has_wiki":true,"has_pages":false,"license":null,"open_issues_count":0,"default_branch":"master","namespace":"oschina","name_with_namespace":"OSCHINA/git-osc","path_with_namespace":"oschina/git-osc"},"project":{"id":151,"name":"Gitee FeedBack","path":"git-osc","full_name":"oschina/git-osc","owner":{"login":"oschina-org","avatar_url":"https://gitee.com/assets/favicon.ico","html_url":"https://gitee.com/oschina-org","type":"User","site_admin":false,"id":1,"name":"Gitee","email":"gitee@gitee.com","username":"oschina-org","user_name":"oschina-org","url":"https://gitee.com/oschina-org"},"private":false,"html_url":"https://gitee.com/oschina/git-osc","url":"https://gitee.com/oschina/git-osc","description":"","fork":false,"created_at":"2020-04-15T21:09:40+08:00","updated_at":"2020-04-15T21:09:40+08:00","pushed_at":"2020-04-15T21:09:40+08:00","git_url":"git://gitee.com:oschina/git-osc.git","ssh_url":"git@gitee.com:oschina/git-osc.git","clone_url":"https://gitee.com/oschina/git-osc.git","svn_url":"svn://gitee.com/oschina/git-osc","git_http_url":"https://gitee.com/oschina/git-osc.git","git_ssh_url":"git@gitee.com:oschina/git-osc.git","git_svn_url":"svn://gitee.com/oschina/git-osc","homepage":null,"stargazers_count":11,"watchers_count":12,"forks_count":0,"language":"ruby","has_issues":true,"has_wiki":true,"has_pages":false,"license":null,"open_issues_count":0,"default_branch":"master","namespace":"oschina","name_with_namespace":"OSCHINA/git-osc","path_with_namespace":"oschina/git-osc"},"user_id":1,"user_name":"Gitee","user":{"id":1,"name":"Gitee","email":"gitee@gitee.com","username":"oschina-org","user_name":"oschina-org","url":"https://gitee.com/oschina-org"},"pusher":{"id":1,"name":"Gitee","email":"gitee@gitee.com","username":"oschina-org","user_name":"oschina-org","url":"https://gitee.com/oschina-org"},"sender":{"login":"oschina-org","avatar_url":"https://gitee.com/assets/favicon.ico","html_url":"https://gitee.com/oschina-org","type":"User","site_admin":false,"id":1,"name":"Gitee","email":"gitee@gitee.com","username":"oschina-org","user_name":"oschina-org","url":"https://gitee.com/oschina-org"},"enterprise":{"name":"OSCHINA","url":"https://gitee.com/oschina"},"hook_name":"push_hooks","hook_id":928268,"hook_url":"https://gitee.com/rothub/index/hooks/928268/edit","password":"","timestamp":"1646657144174","sign":"mNR0yK7W4UlZ4E4aehEVtupxLaE73siwbGgDVSloTVE="}';

        $request = Request::create('', 'POST', [], [], [], [], $content);
        $request->headers->replace(json_decode($headers, true));
        return $request;
    }

    protected function config()
    {
        return [
            'github' => [
                'agent' => 'GitHub-Hookshot',
                'branch' => 'main',
                'log' => './webhook.log',
                'secret' => '437EBE55A75911E8',
                'repositories' => [
                    [
                        'repository' => 'rothub/index',
                        'dir' => '/www/wwwroot/rothub.com',
                        'branch' => 'main',
                        'log' => './webhook.log',
                        'secret' => '437EBE55A75911E8',
                    ],
                ],
            ],
        ];
    }
}
