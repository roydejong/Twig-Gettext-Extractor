<?php

/**
 * This file is part of the Twig Gettext utility.
 *
 *  (c) Saša Stamenković <umpirsky@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Gettext\Loader;

/**
 * Loads template from the filesystem.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class Filesystem extends \Twig_Loader_Filesystem
{
    /**
     * Hacked find template to allow loading templates by absolute path.
     *
     * @param string $name template name or absolute path
     * @param bool $throw
     * @throws \Twig_Error_Loader
     * @return string
     */
    protected function findTemplate($name, $throw = true)
    {
        // normalize name
        $name = preg_replace('#/{2,}#', '/', strtr($name, '\\', '/'));

        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        $validateNameMethod = new \ReflectionMethod($this, 'validateName');
        $validateNameMethod->setAccessible(true);
        $validateNameMethod->invoke($this, $name);

        $namespace = '__main__';
        if (isset($name[0]) && '@' == $name[0]) {
            if (false === $pos = strpos($name, '/')) {
                throw new \InvalidArgumentException(sprintf('Malformed namespaced template name "%s" (expecting "@namespace/template_name").', $name));
            }

            $namespace = substr($name, 1, $pos - 1);

            $name = substr($name, $pos + 1);
        }

        if (!isset($this->paths[$namespace])) {
            throw new \Twig_Error_Loader(sprintf('There are no registered paths for namespace "%s".', $namespace));
        }

        if (is_file($name)) {
            return $this->cache[$name] = $name;
        }

        return __DIR__.'/../Test/Fixtures/twig/empty.twig';
    }
}
