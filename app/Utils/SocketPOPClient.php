<?php 
/** 
 * 类名：SocketPOPClient 
 * 功能：POP3 协议客户端的基本操作类 
 * 作者：heiyeluren <http://blog.csdn.net/heiyeshuwu> 
 * 时间：2006-7-3 
 * 参考：RFC 2449, Uebimiau 
 * 授权：BSD License 
 */

namespace App\Utils;

class SocketPOPClient 
{ 
     public $strMessage    = ''; 
     public $intErrorNum   = 0; 
     public $bolDebug      = false; 
     
     public $strEmail      = ''; 
     public $strPasswd     = ''; 
     public $strHost       = ''; 
     public $intPort       = 110; 
     public $intConnSecond = 30; 
     public $intBuffSize   = 8192;
     public $resHandler    = NULL; 
     public $bolIsLogin    = false; 
     public $strRequest    = ''; 
     public $strResponse   = ''; 
     public $arrRequest    = array(); 
     public $arrResponse   = array();

    //--------------- 
    // 基础操作 
    //---------------
    //构造函数 
    function __construct($strLoginEmail, $strLoginPasswd, $strPopHost='', $intPort='') 
    {
        $this->strEmail  = trim(strtolower($strLoginEmail)); 
        $this->strPasswd = trim($strLoginPasswd); 
        $this->strHost   = trim(strtolower($strPopHost));
        if ($this->strEmail=='' || $this->strPasswd=='') 
        { 
            $this->setMessage('Email address or Passwd is empty', 1001); 
            return false; 
        } 
        if (!preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/i", $this->strEmail)) 
        { 
            $this->setMessage('Email address invalid', 1002); 
            return false; 
        } 
        if ($this->strHost=='') 
        { 
            $this->strHost = substr(strrchr($this->strEmail, "@"), 1); 
        } 
        if ($intPort!='') 
        { 
            $this->intPort = $intPort; 
        } 
        $this->connectHost(); 
    } 
     
    //连接服务器 
    function connectHost() 
    { 
        if ($this->bolDebug) 
        { 
            echo "Connection ".$this->strHost." ...\r\n"; 
        } 
        if (!$this->getIsConnect()) 
        { 
            if ($this->strHost=='' || $this->intPort=='') 
            { 
                $this->setMessage('POP3 host or Port is empty', 1003); 
                return false;             
            } 
            
            $this->resHandler = @fsockopen($this->strHost, $this->intPort, $this->intErrorNum, $this->strMessage, $this->intConnSecond);
            if (!$this->resHandler)
            {
                $strErrMsg = 'Connection POP3 host: '.$this->strHost.' failed'; 
                $intErrNum = 2001; 
                $this->setMessage($strErrMsg, $intErrNum); 
                return false; 
            } 
            $this->getLineResponse(); 
            if (!$this->getRestIsSucceed()) 
            { 
                return false; 
            } 
        } 
        return true; 
    }
    //关闭连接 
    function closeHost() 
    { 
        if ($this->resHandler) 
        { 
            fclose($this->resHandler); 
        } 
        return true; 
    }
    //发送指令 
    function sendCommand($strCommand) 
    { 
        if ($this->bolDebug) 
        { 
            if (!preg_match("/PASS/", $strCommand)) 
            { 
                echo "Send Command: ".$strCommand."\r\n"; 
            } 
            else 
            { 
                echo "Send Command: PASS ******\r\n"; 
            }
        } 
        if (!$this->getIsConnect()) 
        { 
            return false; 
        } 
        if (trim($strCommand)=='') 
        { 
            $this->setMessage('Request command is empty', 1004); 
            return false; 
        } 
        $this->strRequest = $strCommand."\r\n"; 
        $this->arrRequest[] = $strCommand;
        echo ($this->strRequest);
        fputs($this->resHandler, $this->strRequest); 
        return true; 
    }
    //提取响应信息第一行 
    function getLineResponse() 
    { 
        if (!$this->getIsConnect()) 
        { 
            return false; 
        } 
        $this->strResponse = fgets($this->resHandler, $this->intBuffSize);
        $this->arrResponse[] = $this->strResponse;
        return $this->strResponse;
    }
    //提取若干响应信息,$intReturnType是返回值类型, 1为字符串, 2为数组 
    function getRespMessage($intReturnType) 
    { 
        if (!$this->getIsConnect()) 
        { 
            return false; 
        } 
        if ($intReturnType == 1) 
        { 
            $strAllResponse = ''; 
            while(!feof($this->resHandler)) 
            { 
                $strLineResponse = $this->getLineResponse(); 
                if (preg_match("/^\+OK/", $strLineResponse)) 
                { 
                    continue; 
                } 
                if (trim($strLineResponse)=='.') 
                { 
                    break; 
                } 
                $strAllResponse .= $strLineResponse; 
            } 
            return $strAllResponse; 
        } 
        else 
        { 
            $arrAllResponse = array(); 
            while(!feof($this->resHandler)) 
            { 
                $strLineResponse = $this->getLineResponse(); 
                if (preg_match("/^\+OK/", $strLineResponse)) 
                { 
                    continue; 
                } 
                if (trim($strLineResponse)=='.') 
                { 
                    break; 
                } 
                $arrAllResponse[] = $strLineResponse; 
            } 
            return $arrAllResponse;             
        } 
    }
    //提取请求是否成功 
    function getRestIsSucceed($strRespMessage='') 
    { 
        if (trim($strRespMessage)=='')
        { 
            if ($this->strResponse=='') 
            { 
                $this->getLineResponse(); 
            } 
            $strRespMessage = $this->strResponse; 
        } 
        if (trim($strRespMessage)=='') 
        { 
            $this->setMessage('Response message is empty', 2003); 
            return false; 
        } 
        if (!preg_match("/^\+OK/", $strRespMessage)) 
        { 
            $this->setMessage($strRespMessage, 2000); 
            return false; 
        } 
        return true; 
    }
    //获取是否已连接 
    function getIsConnect() 
    { 
        if (!$this->resHandler) 
        { 
            $this->setMessage("Nonexistent availability connection handler", 2002); 
            return false; 
        } 
        return true; 
    }

    //设置消息 
    function setMessage($strMessage, $intErrorNum) 
    { 
        if (trim($strMessage)=='' || $intErrorNum=='') 
        { 
            return false; 
        } 
        $this->strMessage    = $strMessage; 
        $this->intErrorNum    = $intErrorNum; 
        return true; 
    }
    //获取消息 
    function getMessage() 
    { 
        return $this->strMessage; 
    }
    //获取错误号 
    function getErrorNum() 
    { 
        return $this->intErrorNum; 
    }
    //获取请求信息 
    function getRequest() 
    { 
        return $this->strRequest;         
    }
    //获取响应信息 
    function getResponse() 
    { 
        return $this->strResponse; 
    }

    //--------------- 
    // 邮件原子操作 
    //---------------
    //登录邮箱 
    function popLogin() 
    {
        if (!$this->getIsConnect())
        {
            return false; 
        } 
        $this->sendCommand("USER ".$this->strEmail); 
        $this->getLineResponse(); 
        $bolUserRight = $this->getRestIsSucceed();
        $this->sendCommand("PASS ".$this->strPasswd); 
        $this->getLineResponse();
        $bolPassRight = $this->getRestIsSucceed();
        if (!$bolUserRight || !$bolPassRight)
        { 
            $this->setMessage($this->strResponse, 2004); 
            return false; 
        }         
        $this->bolIsLogin = true; 
        return true; 
    }
    //退出登录 
    function popLogout() 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        $this->sendCommand("QUIT"); 
        $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        return true; 
    }
    //获取是否在线 
    function getIsOnline() 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        $this->sendCommand("NOOP"); 
        $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        return true;         
    }
    //获取邮件数量和字节数(返回数组) 
    function getMailSum($intReturnType=2) 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        $this->sendCommand("STAT"); 
        $strLineResponse = $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        if ($intReturnType==1) 
        { 
            return     $this->strResponse; 
        } 
        else 
        { 
            $arrResponse = explode(" ", $this->strResponse); 
            if (!is_array($arrResponse) || count($arrResponse)<=0) 
            { 
                $this->setMessage('STAT command response message is error', 2006); 
                return false; 
            } 
            return array($arrResponse[1], $arrResponse[2]); 
        } 
    }
    //获取指定邮件得Session Id 
    function getMailSessId($intMailId, $intReturnType=2) 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        if (!$intMailId = intval($intMailId)) 
        { 
            $this->setMessage('Mail message id invalid', 1005); 
            return false; 
        } 
        $this->sendCommand("UIDL ". $intMailId); 
        $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        if ($intReturnType == 1) 
        { 
            return     $this->strResponse; 
        } 
        else 
        { 
            $arrResponse = explode(" ", $this->strResponse); 
            if (!is_array($arrResponse) || count($arrResponse)<=0) 
            { 
                $this->setMessage('UIDL command response message is error', 2006); 
                return false; 
            } 
            return array($arrResponse[1], $arrResponse[2]); 
        } 
    }
    //取得某个邮件的大小 
    function getMailSize($intMailId, $intReturnType=2) 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        $this->sendCommand("LIST ".$intMailId); 
        $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        if ($intReturnType == 1) 
        { 
            return $this->strResponse; 
        } 
        else 
        { 
            $arrMessage = explode(' ', $this->strResponse); 
            return array($arrMessage[1], $arrMessage[2]); 
        } 
    }
    //获取邮件基本列表数组 
    function getMailBaseList($intReturnType=2) 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        $this->sendCommand("LIST"); 
        $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        return $this->getRespMessage($intReturnType); 
    }
    //获取指定邮件所有信息，intReturnType是返回值类型，1是字符串,2是数组 
    function getMailMessage($intMailId, $intReturnType=1) 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        if (!$intMailId = intval($intMailId)) 
        { 
            $this->setMessage('Mail message id invalid', 1005); 
            return false; 
        } 
        $this->sendCommand("RETR ". $intMailId); 
        $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        return $this->getRespMessage($intReturnType); 
    }
    //获取某邮件前指定行, $intReturnType 返回值类型，1是字符串，2是数组 
    function getMailTopMessage($intMailId, $intTopLines=10, $intReturnType=1) 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        if (!$intMailId=intval($intMailId) || !$intTopLines=int($intTopLines)) 
        { 
            $this->setMessage('Mail message id or Top lines number invalid', 1005); 
            return false; 
        } 
        $this->sendCommand("TOP ". $intMailId ." ". $intTopLines); 
        $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        return $this->getRespMessage($intReturnType); 
    }
    //删除邮件 
    function delMail($intMailId) 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        if (!$intMailId=intval($intMailId)) 
        { 
            $this->setMessage('Mail message id invalid', 1005); 
            return false; 
        } 
        $this->sendCommand("DELE ".$intMailId); 
        $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        return true; 
    }
    //重置被删除得邮件标记为未删除 
    function resetDeleMail() 
    { 
        if (!$this->getIsConnect() && $this->bolIsLogin) 
        { 
            return false; 
        } 
        $this->sendCommand("RSET"); 
        $this->getLineResponse(); 
        if (!$this->getRestIsSucceed()) 
        { 
            return false; 
        } 
        return true;         
    }
    //--------------- 
    // 调试操作 
    //---------------
    //输出对象信息 
    function printObject() 
    { 
        print_r($this); 
        exit; 
    }
    //输出错误信息 
    function printError() 
    { 
        echo "[Error Msg] :      <br>\n";
        echo "[Error Num] :  <br>\n";
        exit; 
    }
    //输出主机信息 
    function printHost() 
    { 
        echo "[Host]  : $this->strHost <br>\n"; 
        echo "[Port]  : $this->intPort <br>\n"; 
        echo "[Email] : $this->strEmail <br>\n"; 
        echo "[Passwd] : ******** <br>\n"; 
        exit; 
    }
    //输出连接信息 
    function printConnect() 
    { 
        echo "[Connect] : $this->resHandler <br>\n"; 
        echo "[Request] : $this->strRequest <br>\n"; 
        echo "[Response] : $this->strResponse <br>\n"; 
        exit; 
    } 
}