<?php
class GoogleCalendar {
    public $data;
    public $xml;

    public function __construct($login=null, $magicCookie="") {
        $this->data = array();

        if(!is_null($login)) {
            $this->login = $login;
        }
        $this->magicCookie = $magicCookie;
    }

    protected function getAuthHeader() {
        return 'Authorization:  GoogleLogin auth="' . $this->login->getAuth() . '"';
    }
    protected function getFeedEmail() {
        return $this->altEmail ? $this->altEmail : $this->login->email;
    }
    protected function getETagFromHeader($retFields) {
        return $this->getHeaderFromRegex($retFields, "/^ETag:\s*(.*?)$/");
    }
    protected function getEditLinkFromHeader($retFields) {
        return $this->getHeaderFromRegex($retFields, "/^Location:\s*(.*?)$/");
    }

    protected function getHeaderFromRegex($retFields, $regex) {
        if(is_array($retFields)) {
            foreach($retFields as $header) {
                $matches = array();

                if(preg_match("$regex", $header, $matches)) {
                    return $matches[1];
                }
            }
        }else {
            throw new Exception("The header could not be found because the header array was invalid.");
        }

    }
    public function addEvent($params) {
        $url = "http://www.google.com/calendar/feeds/{$this->getFeedEmail()}/private/full";
      
        $xml = "<entry xmlns='http://www.w3.org/2005/Atom' xmlns:gd='http://schemas.google.com/g/2005'>  
              <category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2005#event'></category>  
              <title type='text'>{$params['title']}</title> 
              <content type='text'>{$params['content']}</content>
			  <gd:when startTime='{$params["startTime"]}' endTime='{$params["endTime"]}'>
			  </gd:when>
              <gd:transparency value='http://schemas.google.com/g/2005#event.transparent'>  
              </gd:transparency>  
              <gd:eventstatus value='http://schemas.google.com/g/2005#event.confirmed'>  
              </gd:eventstatus>  
              <gd:where valuestring='{$params['where']}'></gd:where> 
            </entry>
                ";
        $ret = $this->calPostRequest($url, $xml);
        $matches = array();
        if(preg_match('/gsessionid=(.*?)\s+/', $ret, $matches)) {
            $url .= "?gsessionid={$matches[1]}";
            $ret = $this->calPostRequest($url, $xml);
        }
        $retFields = explode("\n", $ret);
        $entryXML = simplexml_load_string($retFields[count($retFields)-1]);
        return array(
                "id"=> (string)$entryXML->id,
                "etag"=> $this->getETagFromHeader($retFields),
                "link"=> $this->getEditLinkFromHeader($retFields)
                );
    }

    public function deleteEvent($url) {
        return $this->calDeleteRequest($url);
    }

    public function calGetRequest($url) {
        $curlOpts = array();
        return $this->calCurlRequest($url, $curlOpts);
    }

    public function calPostRequest($url, $data) {
        $curlOpts = array(
            CURLOPT_POST=> true,
            CURLOPT_POSTFIELDS=> $data,
            CURLOPT_HEADER=> true,
            CURLOPT_HTTPHEADER=> array('GData-Version:  2', $this->getAuthHeader(), 'Content-Type:  application/atom+xml')
        );
        return $this->calCurlRequest($url, $curlOpts);
    }

    public function calDeleteRequest($url) {
        $curlOpts = array(
            CURLOPT_CUSTOMREQUEST=> "DELETE",
            CURLOPT_HTTPHEADER=> array('GData-Version:  2', $this->getAuthHeader(), 'If-Match:  *')
        );
        return $this->calCurlRequest($url, $curlOpts);
    }
    private function calCurlRequest($url, $curlOpts) {
        if(!array_key_exists(CURLOPT_FOLLOWLOCATION, $curlOpts)) {
            $curlOpts[CURLOPT_FOLLOWLOCATION] = true;
        }
        if(!array_key_exists(CURLOPT_RETURNTRANSFER, $curlOpts)) {
            $curlOpts[CURLOPT_RETURNTRANSFER] = true;
        }
        if(!array_key_exists(CURLOPT_HEADER, $curlOpts)) {
            $curlOpts[CURLOPT_HEADER] = false;
        }
        if(!array_key_exists(CURLOPT_HTTPHEADER, $curlOpts)) {
            $curlOpts[CURLOPT_HTTPHEADER] = array('GData-Version:  2', $this->getAuthHeader());
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, $curlOpts);
        $ret = curl_exec($ch);
        curl_close($ch);

        return $ret;
    }

    public function __get($name) {
        return $this->data[$name];
    }

    public function __set($name, $val) {
        $this->data[$name] = $val;
    }
}
?>
