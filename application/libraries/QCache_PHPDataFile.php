<?php
/**
 * ���� QCache_PHPDataFile ��
 *
 * @link http://qeephp.com/
 * @copyright Copyright (c) 2006-2009 Qeeyuan Inc. {@link http://www.qeeyuan.com}
 * @license New BSD License {@link http://qeephp.com/license/}
 * @package cache
 */

/**
 * QCache_PHPDataFile ���� .php �ļ������� PHP �ı�������
 *
 * �� QCache_File ��ȣ�QCache_PHPDataFile �ٶȸ��죬��ֻ�ܱ�����Ч�� PHP �������ݡ�
 *
 * @author YuLei Liao <liaoyulei@qeeyuan.com>
 * @package cache
 */
class QCache_PHPDataFile
{
    /**
     * Ĭ�ϵĻ������
     *
     * -  life_time: ������Чʱ�䣨�룩��Ĭ��ֵ 900
     *    �������Ϊ 0 ��ʾ��������ʧЧ������Ϊ -1 �������� 0 С��ֵ���ʾ����黺����Ч�ڡ�
     *
     * -  cache_dir: ����Ŀ¼������ָ����
     *
     * @var array
     */
    protected $_default_policy = array
    (
        'life_time'         => -1,
        'cache_dir'         => NULL,
    );

    /**
     * ���캯��
     *
     * @param Ĭ�ϵĻ������ $default_policy
     */
    function __construct(array $default_policy = null)
    {
    	$default_policy['cache_dir'] = __DIR__.'/../../storage/cache';
    	
    		
        if (!is_null($default_policy))
        {
            $this->_default_policy = array_merge($this->_default_policy, $default_policy);
        }

        if (empty($this->_default_policy['cache_dir']))
        {
            $this->_default_policy['cache_dir'] = root_path().'assets/';
        }
        $this->_default_policy['cache_dir'] = rtrim($this->_default_policy['cache_dir'], '/\\');
    }

    /**
     * д�뻺��
     *
     * @param string $id
     * @param mixed $data
     * @param array $policy
     */
    function set($id, $data, array $policy = null)
    {
        $policy = $this->_policy($policy);
        $path = $this->_path($id, $policy);

        $content = array(
            'expired' => time() + $policy['life_time'],
            'data'    => $data,
        );
        $content = '<?php return ' . var_export($content, true) . ';';

        // д�뻺�棬��ȥ������ո�
        file_put_contents($path, $content, LOCK_EX);
        // file_put_contents($path, php_strip_whitespace($path), LOCK_EX);
        clearstatcache();
    }

    /**
     * ��ȡ���棬ʧ�ܻ򻺴���ʧЧʱ���� false
     *
     * @param string $id
     * @param array $policy
     *
     * @return mixed
     */
    function get($id, array $policy = null)
    {
        $policy = $this->_policy($policy);
        $path = $this->_path($id, $policy);

        if (!is_file($path)) { return false; }

        $data = include($path);
        if (!is_array($data) || !isset($data['expired'])) return false;

        if ($policy['life_time'] < 0)
        {
            return $data['data'];
        }
        else
        {
            return ($data['expired'] > time()) ? $data['data'] : false;
        }
    }

    /**
     * ɾ��ָ���Ļ���
     *
     * @param string $id
     * @param array $policy
     */
    function remove($id, array $policy = null)
    {
        $path = $this->_path($id, $this->_policy($policy));
        if (is_file($path)) { unlink($path); }
    }

    /**
     * ȷ�������ļ���
     *
     * @param string $id
     * @param array $policy
     *
     * @return string
     */
    protected function _path($id, array $policy)
    {
        return $policy['cache_dir'] . DS . 'cache_' . md5($id) . '_data.php';
    }

    /**
     * ������Ч�Ĳ���ѡ��
     *
     * @param array $policy
     * @return array
     */
    protected function _policy(array $policy = null)
    {
        return !is_null($policy) ? array_merge($this->_default_policy, $policy) : $this->_default_policy;
    }
}
