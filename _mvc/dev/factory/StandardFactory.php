<?php

namespace Concerto\pattern\builder;

use Closure;
use ReflectionParameter;
use Psr\container\ContainerInterface;
use Concerto\pattern\builder\StandardFactoryInterface;

class StandardFactory implements StandardFactoryInterface
{

  /**
   *  @var ContainerInterface $container
   */
  private ContainerInterface $container;

  /**
   *  @var 
   */
  private array $ 





  /**
   *  __construct
   *
   *  @param ContainerInterface $container
   */
  public function __construct(
    containerInterface $container
  ) {
    $this->container = $container;
  }

  /**
   *  {inherit}
   */ 
  public function make(
    string $namespace,
    ?array $arguments
  ): object; {

    return $this->resolve();
    
  }

   /**
   * Resolve the given type from the container.
   *
   * @param string|callable $abstract
   * @param array $parameters
   * @return mixed
   */
    protected function resolve(
      string|callable $abstract,
      array $parameters = [], 
    ): mixed {
          $concrete = $this->getConcrete($abstract);
       
       if ($this->isBuildable($concrete, $abstract)) {
          $object = $this->build($concrete);
       } else {
          $object = $this->make($concrete);
       }
       
      $this->resolved[$abstract] = true;
      return $object;
  }

   /**
   * Get the concrete type for a given abstract.
   *
   * @param string|callable $abstract
   * @return mixed
   */
   protected function getConcrete(
    string|callable $abstract
   ):mixed {
     if ($this->container->has($abstract)) {
       return $this->container->get($abstract);
     }
    return $abstract; 
   }

   /**
   * Determine if the given concrete is buildable.
   *
   * @param mixed $concrete
   * @param string $abstract
   * @return bool
   */
   protected function isBuildable(
    mixed $concrete,
    string $abstract
   ): bool {
        return $concrete === $abstract ||
            $concrete instanceof Closure;
   }
   
   /**
   * Instantiate a concrete instance of the given type.
   *
   * @param Closure|string $concrete
   * @return mixed
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   * @throws \Illuminate\Contracts\Container\CircularDependencyException
   */
   public function build(
    Closure|string $concrete)
   ):mixed{
       if ($concrete instanceof Closure) {
        return $concrete($this, $this->getLastParameterOverride());
       }
       
       try {
        $reflector = new ReflectionClass($concrete);
       } catch (ReflectionException $e) {
        throw new BindingResolutionException(
            "Target class [$concrete] does not exist.", 0, $e);
       }
       
       if (! $reflector->isInstantiable()) {
        return $this->notInstantiable($concrete);
       }
   
        while resolving [{$concrete}].")";
        }
        
       $this->buildStack[] = $concrete;
       $constructor = $reflector->getConstructor();
       
        if (is_null($constructor)) {
            array_pop($this->buildStack);
            return new $concrete;
       }

       $dependencies = $constructor->getParameters();

       try {
            $instances = $this->resolveDependencies($dependencies);
       } catch (BindingResolutionException $e) {
            array_pop($this->buildStack);
            throw $e;
       }
       
       array_pop($this->buildStack);
       return $reflector->newInstanceArgs($instances);
   }

   /**
   * Resolve all of the dependencies from the ReflectionParameters.
   *
   * @param ReflectionParameter[] $dependencies
   * @return array
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
   protected function resolveDependencies(
    array $dependencies
   ):array {
       $results = [];
       
       foreach ($dependencies as $dependency) {
           if ($this->hasParameterOverride($dependency)) {
               $results[] = $this->getParameterOverride($dependency);
               continue;
           }

           $result = is_null(Util::getParameterClassName($dependency))
               ? $this->resolvePrimitive($dependency)
               : $this->resolveClass($dependency);

           if ($dependency->isVariadic()) {
            $results = array_merge($results, $result);
           } else {
            $results[] = $result;
           }
       }
       return $results;
   }

   /**
   * Determine if the given dependency has a parameter override.
   *
   * @param ReflectionParameter $dependency
   * @return bool
   */
   protected function hasParameterOverride(
    ReflectionParameter $dependency
   ):bool{
       return array_key_exists(
           $dependency->name,
           $this->getLastParameterOverride()
       );
   }

   /**
   * Get a parameter override for a dependency.
   *
   * @param ReflectionParameter $dependency
   * @return mixed
   */
   protected function getParameterOverride(
    ReflectionParameter $dependency
   ):mixed{
        return $this->getLastParameterOverride()[$dependency->name];
   }

   /**
   * Get the last parameter override.
   *
   * @return array
   */
   protected function getLastParameterOverride():array
   {
        return count($this->with) ? end($this->with) : [];
   }

   /**
   * Resolve a non-class hinted primitive dependency.
   *
   * @param ReflectionParameter $parameter
   * @return mixed
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
   protected function resolvePrimitive(
    ReflectionParameter $parameter
   ):mixed{
       if (! is_null($concrete =
           $this->getContextualConcrete('$'.$parameter->getName()))) {
           return Util::unwrapIfClosure($concrete, $this);
       }

       if ($parameter->isDefaultValueAvailable()) {
           return $parameter->getDefaultValue();
       }
       $this->unresolvablePrimitive($parameter);
   }

   /**
   * Resolve a class based dependency from the container.
   *
   * @param ReflectionParameter $parameter
   * @return mixed
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
   protected function resolveClass(
    ReflectionParameter $parameter
   ):mixed {
       try {
           return $parameter->isVariadic()
           ? $this->resolveVariadicClass($parameter)
           : $this->make(Util::getParameterClassName($parameter));
       }
       catch (BindingResolutionException $e) {
           if ($parameter->isDefaultValueAvailable()) {
               array_pop($this->with);
               return $parameter->getDefaultValue();
           }
           
           if ($parameter->isVariadic()) {
               array_pop($this->with);
               return [];
           }
           throw $e;
       }
   }

   /**
   * Resolve a class based variadic dependency from the container.
   *
   * @param ReflectionParameter $parameter
   * @return mixed
   */
   protected function resolveVariadicClass(
    ReflectionParameter $parameter
   ):mixed{
       $className = Util::getParameterClassName($parameter);
       $abstract = $this->getAlias($className);

       if (! is_array($concrete = $this->getContextualConcrete($abstract))) {
        return $this->make($className);
       }

       return array_map(function ($abstract) {
        return $this->resolve($abstract);
       }, $concrete);
   }

   /**
   * Throw an exception that the concrete is not instantiable.
   *
   * @param string $concrete
   * @return void
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
   protected function notInstantiable(
    string $concrete
   ): void{
       if (! empty($this->buildStack)) {
           $previous = implode(', ', $this->buildStack);
           $message = "Target [$concrete] is not instantiable while building
           [$previous].";
       } else {
        $message = "Target [$concrete] is not instantiable.";
       }
       throw new BindingResolutionException($message);
   }

   /**
   * Throw an exception for an unresolvable primitive.
   *
   * @param ReflectionParameter $parameter
   * @return void
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
   protected function unresolvablePrimitive(
    ReflectionParameter $parameter
       ):void{
       $message = "Unresolvable dependency resolving [$parameter] in class
        {$parameter->getDeclaringClass()->getName()}";
       throw new BindingResolutionException($message);
   }

   /**
   * Register a new before resolving callback for all types.
   *
   * @param Closure|string $abstract
   * @param Closure|null $callback
   * @return void
   */
   public function beforeResolving(
    Closure|string $abstract,
    ?Closure $callback = null
   ):void{
       if (is_string($abstract)) {
           $abstract = $this->getAlias($abstract);
       }

       if ($abstract instanceof Closure && is_null($callback)) {
           $this->globalBeforeResolvingCallbacks[] = $abstract;
       } else {
           $this->beforeResolvingCallbacks[$abstract][] = $callback;
       }
   }

   /**
   * Register a new resolving callback.
   *
   * @param Closure|string $abstract
   * @param Closure|null $callback
   * @return void
   */
   public function resolving(
    Closure|string $abstract,
    ?Closure $callback = null
   ):void{
       if (is_string($abstract)) {
           $abstract = $this->getAlias($abstract);
       }

       if (is_null($callback) && $abstract instanceof Closure) {
           $this->globalResolvingCallbacks[] = $abstract;
       } else {
           $this->resolvingCallbacks[$abstract][] = $callback;
       }
   }

   /**
   * Register a new after resolving callback for all types.
   *
   * @param Closure|string $abstract
   * @param Closure|null $callback
   * @return void
   */
   public function afterResolving(
        Closure|string $abstract,
        ?Closure $callback = null
    ):void{
       if (is_string($abstract)) {
           $abstract = $this->getAlias($abstract);
       }

       if ($abstract instanceof Closure && is_null($callback)) {
           $this->globalAfterResolvingCallbacks[] = $abstract;
       } else {
           $this->afterResolvingCallbacks[$abstract][] = $callback;
       }
   }

   /**
   * Fire all of the before resolving callbacks.
   *
   * @param string $abstract
   * @param array $parameters
   * @return void
   */
   protected function fireBeforeResolvingCallbacks(
    string $abstract,
    array $parameters = []
   ):void{
       $this->fireBeforeCallbackArray(
        $abstract, $parameters,
        $this->globalBeforeResolvingCallbacks
       );

       foreach ($this->beforeResolvingCallbacks as $type => $callbacks) {
           if ($type === $abstract || is_subclass_of($abstract, $type)) {
           $this->fireBeforeCallbackArray($abstract, $parameters, $callbacks);
           }
       }
   }

   /**
   * Fire an array of callbacks with an object.
   *
   * @param string $abstract
   * @param array $parameters
   * @param array $callbacks
   * @return void
   */
   protected function fireBeforeCallbackArray($abstract, $parameters,
   array $callbacks)
   {
   foreach ($callbacks as $callback) {
   $callback($abstract, $parameters, $this);
   }
   }

   /**
   * Fire all of the resolving callbacks.
   *
   * @param string $abstract
   * @param mixed $object
   * @return void
   */
   protected function fireResolvingCallbacks(
    string $abstract,
    mixed $object
   ):void{
       $this->fireCallbackArray($object, $this->globalResolvingCallbacks);
       
       $this->fireCallbackArray(
       $object, $this->getCallbacksForType(
        $abstract,
        $object,
        $this->resolvingCallbacks)
       );
       $this->fireAfterResolvingCallbacks($abstract, $object);
   }

   /**
   * Fire all of the after resolving callbacks.
   *
   * @param string $abstract
   * @param mixed $object
   * @return void
   */
   protected function fireAfterResolvingCallbacks(
    string $abstract,
    mixed $object
   ):void{
       $this->fireCallbackArray(
        $object,
        $this->globalAfterResolvingCallbacks
        );
       $this->fireCallbackArray(
        $object,
        $this->getCallbacksForType(
            $abstract,
            $object,
            $this->afterResolvingCallbacks
        )
       );
   }

   /**
   * Get all callbacks for a given type.
   *
   * @param string $abstract
   * @param object $object
   * @param array $callbacksPerType
   *
   * @return array
   */
   protected function getCallbacksForType(
    string　$abstract,
    object　$object,
    array $callbacksPerType
   ):array {
       $results = [];
       foreach ($callbacksPerType as $type => $callbacks) {
           if ($type === $abstract || $object instanceof $type) {
               $results = array_merge($results, $callbacks);
           }
       }
       return $results;
   }

   /**
   * Fire an array of callbacks with an object.
   *
   * @param mixed $object
   * @param array $callbacks
   * @return void
   */
   protected function fireCallbackArray(
    mixed $object,
    array $callbacks
   ):void {
       foreach ($callbacks as $callback) {
           $callback($object, $this);
       }
   }

   /**
   * Get the container's bindings.
   *
   * @return array
   */
   public function getBindings():array
   {
    return $this->bindings;
   }

   /**
   * Get the extender callbacks for a given type.
   *
   * @param string $abstract
   * @return array
   */
   protected function getExtenders(
    string$abstract
   ):array {
       return $this->extenders[$this->getAlias($abstract)] ?? [];
   }

   /**
   * Remove all of the extender callbacks for a given type.
   *
   * @param string $abstract
   * @return void
   */
   public function forgetExtenders(
    string $abstract
   ):void {
    unset($this->extenders[$this->getAlias($abstract)]);
   }

   /**
   * Drop all of the stale instances and aliases.
   *
   * @param string $abstract
   * @return void
   */
   protected function dropStaleInstances(
    string $abstract
   ):void{
        unset($this->instances[$abstract], $this->aliases[$abstract]);
   }

   /**
   * Remove a resolved instance from the instance cache.
   *
   * @param string $abstract
   * @return void
   */
   public function forgetInstance(
    string $abstract
   ):void {
        unset($this->instances[$abstract]);
   }

   /**
   * Clear all of the instances from the container.
   *
   * @return void
   */
   public function forgetInstances():void
   {
    $this->instances = [];
   }

   /**
   * Clear all of the scoped instances from the container.
   *
   * @return void
   */
   public function forgetScopedInstances():void
   {
       foreach ($this->scopedInstances as $scoped) {
           unset($this->instances[$scoped]);
       }
   }
} 
