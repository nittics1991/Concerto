<?php

/**
*   ClassAnalyzer
*
*   @version xxx
*/

namespace DocBlockGenerator\analyzer;

use ReflectionClass;
use DocBlockGenerator\analyzer\AnalyzerInterface;

class ClassAnalyzer implements AnalyzerInterface
{
    
    
    /**
    *   {inherit}
    *
    */
    public function analyze($fqdn)
    {
        $reflectionClass = new ReflectionClass($fqdn);
        
        $class_info->namespace = $reflectionClass->getNamespaceName();
        $class_info->name = $reflectionClass->getName();
        $class_info->line = $reflectionClass->getStartLine();
        $class_info->comment = $reflectionClass->getDocComment();
        
        //class_info->methods
        $methods = $reflectionClass->getMethods();
        $properties = $reflectionClass->getProperties();
        $constants = $reflectionClass->getReflectionConstants();
        
        
        
        
        
    }
    
    /////////////////
    
    
    /**
    *   
    *
    */
    public function analyzeClass()
    {
        $reflectionClass = new ReflectionClass($fqdn);
        
        $class_info->namespace = $reflectionClass->getNamespaceName();
        $class_info->name = $reflectionClass->getName();
        $class_info->line = $reflectionClass->getStartLine();
        $class_info->comment = $reflectionClass->getDocComment();
        
        
        
    }
    
    ///////////////////////
    
    
    
    /**
    *   
    *
    */
    public function analyzeMethod(ReflectionMethod)
    {
        
        $method_info->name = $reflectionMethod->getName();
        $method_info->line = $reflectionMethod->getStartLine();
        $method_info->comment = $reflectionMethod->getDocComment();
        
        $method_info_ext->returnType = $reflectionMethod->getReturnType();
        
        $params = $reflectionMethod->getParameters();
        
        
        
    }
    
    
    
     /**
    *   
    *
    */
    public function analyzeParameter(ReflectionParameter)
    {
        
        $param_info->name = $reflectionProperty->getName();
        $param_info->returnType = $reflectionProperty->getReturnType();
        
    }
    
    
    
    ///////////////////////
    
    
    /**
    *   
    *
    */
    public function analyzeProperty(ReflectionProperty)
    {
        //行が不明
        
        $prop_info->comment = $reflectionClass->getDocComment();
        
        
        $prop_info_ext->name = $reflectionProperty->getName();
        $prop_info_ext->returnType = $reflectionProperty->getReturnType();
        
    }
    
    ///////////////////////
    
    
    /**
    *   
    *
    */
    public function analyzeConstant(ReflectionClassConstant)
    {
        //行が不明
        
        
        $const_info->comment = $reflectionClass->getDocComment();
        
        
        $const_info->name = $reflectionProperty->getName();
        $const_info->returnType = $reflectionProperty->getReturnType();
        
    }
    
    
    
    
    
    
}
