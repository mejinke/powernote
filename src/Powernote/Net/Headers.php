<?php
namespace Powernote\Net;

class Headers
{

    /**
     * ContentType
     *
     * @var string
     */
    protected $contentType;

    /**
     * 状态码
     *
     * @var int
     */
    protected $statusCode;

    /**
     * ContentType类型
     *
     * @var array
     */
    private $contentTypes = [
        
        'ez' => 'application/andrew-inset', 
        'hqx' => 'application/mac-binhex40', 
        'cpt' => 'application/mac-compactpro', 
        'doc' => 'application/msword', 
        'bin' => 'application/octet-stream', 
        'dms' => 'application/octet-stream', 
        'lha' => 'application/octet-stream', 
        'lzh' => 'application/octet-stream', 
        'exe' => 'application/octet-stream', 
        'class' => 'application/octet-stream', 
        'so' => 'application/octet-stream', 
        'dll' => 'application/octet-stream', 
        'oda' => 'application/oda', 
        'pdf' => 'application/pdf', 
        'ai' => 'application/postscript', 
        'eps' => 'application/postscript', 
        'ps' => 'application/postscript', 
        'smi' => 'application/smil', 
        'smil' => 'application/smil', 
        'mif' => 'application/vnd.mif', 
        'xls' => 'application/vnd.ms-excel', 
        'ppt' => 'application/vnd.ms-powerpoint', 
        'wbxml' => 'application/vnd.wap.wbxml', 
        'wmlc' => 'application/vnd.wap.wmlc', 
        'wmlsc' => 'application/vnd.wap.wmlscriptc', 
        'bcpio' => 'application/x-bcpio', 
        'vcd' => 'application/x-cdlink', 
        'pgn' => 'application/x-chess-pgn', 
        'cpio' => 'application/x-cpio', 
        'csh' => 'application/x-csh', 
        'dcr' => 'application/x-director', 
        'dir' => 'application/x-director', 
        'dxr' => 'application/x-director', 
        'dvi' => 'application/x-dvi', 
        'spl' => 'application/x-futuresplash', 
        'gtar' => 'application/x-gtar', 
        'hdf' => 'application/x-hdf', 
        'js' => 'application/x-javascript', 
        'skp' => 'application/x-koan', 
        'skd' => 'application/x-koan', 
        'skt' => 'application/x-koan', 
        'skm' => 'application/x-koan', 
        'latex' => 'application/x-latex', 
        'nc' => 'application/x-netcdf', 
        'cdf' => 'application/x-netcdf', 
        'sh' => 'application/x-sh', 
        'shar' => 'application/x-shar', 
        'swf' => 'application/x-shockwave-flash', 
        'sit' => 'application/x-stuffit', 
        'sv4cpio' => 'application/x-sv4cpio', 
        'sv4crc' => 'application/x-sv4crc', 
        'tar' => 'application/x-tar', 
        'tcl' => 'application/x-tcl', 
        'tex' => 'application/x-tex', 
        'texinfo' => 'application/x-texinfo', 
        'texi' => 'application/x-texinfo', 
        't' => 'application/x-troff', 
        'tr' => 'application/x-troff', 
        'roff' => 'application/x-troff', 
        'man' => 'application/x-troff-man', 
        'me' => 'application/x-troff-me', 
        'ms' => 'application/x-troff-ms', 
        'ustar' => 'application/x-ustar', 
        'src' => 'application/x-wais-source', 
        'xhtml' => 'application/xhtml+xml', 
        'xht' => 'application/xhtml+xml', 
        'zip' => 'application/zip', 
        'au' => 'audio/basic', 
        'snd' => 'audio/basic', 
        'mid' => 'audio/midi', 
        'midi' => 'audio/midi', 
        'kar' => 'audio/midi', 
        'mpga' => 'audio/mpeg', 
        'mp2' => 'audio/mpeg', 
        'mp3' => 'audio/mpeg', 
        'aif' => 'audio/x-aiff', 
        'aiff' => 'audio/x-aiff', 
        'aifc' => 'audio/x-aiff', 
        'm3u' => 'audio/x-mpegurl', 
        'ram' => 'audio/x-pn-realaudio', 
        'rm' => 'audio/x-pn-realaudio', 
        'rpm' => 'audio/x-pn-realaudio-plugin', 
        'ra' => 'audio/x-realaudio', 
        'wav' => 'audio/x-wav', 
        'pdb' => 'chemical/x-pdb', 
        'xyz' => 'chemical/x-xyz', 
        'bmp' => 'image/bmp', 
        'gif' => 'image/gif', 
        'ief' => 'image/ief', 
        'jpeg' => 'image/jpeg', 
        'jpg' => 'image/jpeg', 
        'jpe' => 'image/jpeg', 
        'png' => 'image/png', 
        'tiff' => 'image/tiff', 
        'tif' => 'image/tiff', 
        'djvu' => 'image/vnd.djvu', 
        'djv' => 'image/vnd.djvu', 
        'wbmp' => 'image/vnd.wap.wbmp', 
        'ras' => 'image/x-cmu-raster', 
        'pnm' => 'image/x-portable-anymap', 
        'pbm' => 'image/x-portable-bitmap', 
        'pgm' => 'image/x-portable-graymap', 
        'ppm' => 'image/x-portable-pixmap', 
        'rgb' => 'image/x-rgb', 
        'xbm' => 'image/x-xbitmap', 
        'xpm' => 'image/x-xpixmap', 
        'xwd' => 'image/x-xwindowdump', 
        'igs' => 'model/iges', 
        'iges' => 'model/iges', 
        'msh' => 'model/mesh', 
        'mesh' => 'model/mesh', 
        'silo' => 'model/mesh', 
        'wrl' => 'model/vrml', 
        'vrml' => 'model/vrml', 
        'css' => 'text/css', 
        'html' => 'text/html', 
        'htm' => 'text/html', 
        'asc' => 'text/plain', 
        'txt' => 'text/plain', 
        'rtx' => 'text/richtext', 
        'rtf' => 'text/rtf', 
        'sgml' => 'text/sgml', 
        'sgm' => 'text/sgml', 
        'tsv' => 'text/tab-separated-values', 
        'wml' => 'text/vnd.wap.wml', 
        'wmls' => 'text/vnd.wap.wmlscript', 
        'etx' => 'text/x-setext', 
        'xsl' => 'text/xml', 
        'xml' => 'text/xml', 
        'mpeg' => 'video/mpeg', 
        'mpg' => 'video/mpeg', 
        'mpe' => 'video/mpeg', 
        'qt' => 'video/quicktime', 
        'mov' => 'video/quicktime', 
        'mxu' => 'video/vnd.mpegurl', 
        'avi' => 'video/x-msvideo', 
        'movie' => 'video/x-sgi-movie', 
        'ice' => 'x-conference/x-cooltalk'
    ];

    /**
     * 状态码列表
     * 
     * @var array
     */
    private $statusCodes = [
        
        100 => 'Continue', 
        101 => 'Switching Protocols', 
        102 => 'Processing', 
        200 => 'OK', 
        201 => 'Created', 
        202 => 'Accepted', 
        203 => 'Non-Authoriative Information', 
        204 => 'No Content', 
        205 => 'Reset Content', 
        206 => 'Partial Content', 
        207 => 'Multi-Status', 
        300 => 'Multiple Choices', 
        301 => 'Moved Permanently', 
        302 => 'Found', 
        303 => 'See Other', 
        304 => 'Not Modified', 
        305 => 'Use Proxy', 
        306 => '(Unused)', 
        307 => 'Temporary Redirect', 
        400 => 'Bad Request', 
        401 => 'Unauthorized', 
        402 => 'Payment Granted', 
        403 => 'Forbidden', 
        404 => 'File Not Found', 
        405 => 'Method Not Allowed', 
        406 => 'Not Acceptable', 
        407 => 'Proxy Authentication Required', 
        408 => 'Request Time-out', 
        409 => 'Conflict', 
        410 => 'Gone', 
        411 => 'Length Required', 
        412 => 'Precondition Failed', 
        413 => 'Request Entity Too Large', 
        414 => 'Request-URI Too Large', 
        415 => 'Unsupported Media Type', 
        416 => 'Requested range not satisfiable', 
        417 => 'Expectation Failed', 
        422 => 'Unprocessable Entity', 
        423 => 'Locked', 
        424 => 'Failed Dependency', 
        500 => 'Internal Server Error', 
        501 => 'Not Implemented', 
        502 => 'Bad Gateway', 
        503 => 'Service Unavailable', 
        504 => 'Gateway Timeout', 
        505 => 'HTTP Version Not Supported', 
        507 => 'Insufficient Storage'
    ];

    /**
     * Header列表
     * 
     * @var array
     */
    private $headers;

    /**
     * 初始化函数
     *
     * @param string $contentType
     * @param number $code
     * @throws \Powernote\Net\Exception\Header
     */
    public function __construct($contentType = 'html', $code = 200)
    {
        if (! isset($this->contentTypes[$contentType]))
        {
            throw new \Powernote\Net\Exception\InvalidContentTypeException("ContentType error");
        }
        
        $this->contentType = $this->contentTypes[$contentType];
        $this->statusCode = isset($this->statusCodes[$code]) ? $code : 200;
    }

    /**
     * 添加Header
     *
     * @param string $header
     * @return \Powernote\Net\Headers
     */
    public function add($header)
    {
        if (! in_array($header, $this->headers))
        {
            $this->headers[] = $header;
        }
        return $this;
    }

    public function __toString()
    {
        header('HTTP/1.1 '.$this->statusCode.' '.$this->statusCodes[$this->statusCode]);
        header('Content-type:' . $this->contentType);
        
        return '';
    }
}