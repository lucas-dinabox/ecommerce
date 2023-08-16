<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');

$ini = parse_ini_file('app/config/application.ini', true);

if (isset($ini['general']['allow_cors']) && intval($ini['general']['allow_cors']) === 1) {
    $origins = $ini['frontend']['frontend_urls'];
    $frontOrigin = strtolower($_SERVER['HTTP_ORIGIN'] ?? '');
    $originAllowed = '';
    if (in_array($frontOrigin, $origins)) {
        $originAllowed = $frontOrigin;
    }
    header("Access-Control-Allow-Origin: {$originAllowed}");
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
        exit();
    }
}

// initialization script
require_once 'init.php';

class AdiantiRestServer
{
    public static function run($request)
    {
        $ini      = AdiantiApplicationConfig::get();
        $input    = json_decode(file_get_contents("php://input"), true);
        $request  = array_merge($request, (array) $input);
        $class    = isset($request['class']) ? $request['class']   : '';
        $method   = isset($request['method']) ? $request['method'] : '';
        $headers  = AdiantiCoreApplication::getHeaders();
        $response = NULL;

        $headers['Authorization'] = $headers['Authorization'] ?? ($headers['authorization'] ?? null); // for clientes that send in lowercase (Ex. futter)
        
        try
        {
            if (empty($headers['Authorization']))
            {
                throw new Exception( _t('Authorization error') );
            }
            else
            {
                if (substr($headers['Authorization'], 0, 5) == 'Basic')
                {
                    if (empty($ini['general']['rest_key']))
                    {
                        throw new Exception( _t('REST key not defined') );
                    }
					
                    if ($ini['general']['rest_key'] !== substr($headers['Authorization'], 6))
                    {
                        http_response_code(401);
                        return json_encode( array('status' => 'error', 'data' => _t('Authorization error')));
                    }
                }
                else if (substr($headers['Authorization'], 0, 6) == 'Bearer')
                {
                    ApplicationAuthenticationService::fromToken( substr($headers['Authorization'], 7) );
                }
                else
                {
                    http_response_code(403);
                    throw new Exception( _t('Authorization error') );
                }
            }
            
            $response = AdiantiCoreApplication::execute($class, $method, $request, 'rest');
            if (is_array($response))
            {
                array_walk_recursive($response, ['AdiantiStringConversion', 'assureUnicode']);
            }
            return json_encode( array('status' => 'success', 'data' => $response));
        }
        catch (Exception $e)
        {
            if(200 === http_response_code())
            {
                http_response_code(500);
            }

            return json_encode( array('status' => 'error', 'data' => $e->getMessage()));
        }
        catch (Error $e)
        {
            if(200 === http_response_code())
            {
                http_response_code(500);
            }

            return json_encode( array('status' => 'error', 'data' => $e->getMessage()));
        }
    }
}

print AdiantiRestServer::run($_REQUEST);
