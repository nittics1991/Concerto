<?php

namespace Concerto\pattern\builder;

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

    aaa
  }

   /**
   * Resolve the given type from the container.
   *
   * @param string|callable $abstract
   * @param array $parameters
   * @param bool $raiseEvents
   * @return mixed
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   * @throws \Illuminate\Contracts\Container\CircularDependencyException
   */
   protected function resolve($abstract, $parameters = [], $raiseEvents =
   true)
   {
   $abstract = $this->getAlias($abstract);
   // First we'll fire any event handlers which handle the "before"
   resolving of
   // specific types. This gives some hooks the chance to add various
   extends
   // calls to change the resolution of objects that they're interested
   in.
   if ($raiseEvents) {
   $this->fireBeforeResolvingCallbacks($abstract, $parameters);
   }
   $concrete = $this->getContextualConcrete($abstract);
   $needsContextualBuild = ! empty($parameters) || ! is_null($concrete);
   // If an instance of the type is currently being managed as a singleton
   we'll
   // just return an existing instance instead of instantiating new
   instances
   // so the developer can keep using the same objects instance every
   time.
   if (isset($this->instances[$abstract]) && ! $needsContextualBuild) {
   return $this->instances[$abstract];
   }
   $this->with[] = $parameters;
   if (is_null($concrete)) {
   $concrete = $this->getConcrete($abstract);
   }
   // We're ready to instantiate an instance of the concrete type
   registered for
   // the binding. This will instantiate the types, as well as resolve any
   of
   // its "nested" dependencies recursively until all have gotten
   resolved.
   if ($this->isBuildable($concrete, $abstract)) {
   $object = $this->build($concrete);
   } else {
   $object = $this->make($concrete);
   }
   // If we defined any extenders for this type, we'll need to spin
   through them
   // and apply them to the object being built. This allows for the
   extension
   // of services, such as changing configuration or decorating the
   object.
   foreach ($this->getExtenders($abstract) as $extender) {
   $object = $extender($object, $this);
   }
   // If the requested type is registered as a singleton we'll want to
   cache off
   // the instances in "memory" so we can return it later without creating
   an
   // entirely new instance of an object on each subsequent request for
   it.
   if ($this->isShared($abstract) && ! $needsContextualBuild) {
   $this->instances[$abstract] = $object;
   }
   if ($raiseEvents) {
   $this->fireResolvingCallbacks($abstract, $object);
   }
   // Before returning, we will also set the resolved flag to "true" and
   pop off
   // the parameter overrides for this build. After those two things are
   done
   // we will be ready to return back the fully constructed class
   instance.
   $this->resolved[$abstract] = true;
   array_pop($this->with);
   return $object;
   }
   /**
   * Get the concrete type for a given abstract.
   *
   * @param string|callable $abstract
   * @return mixed
   */
   protected function getConcrete($abstract)
   {
   // If we don't have a registered resolver or concrete for the type,
   we'll just
   // assume each type is a concrete name and will attempt to resolve it
   as is
   // since the container should be able to resolve concretes
   automatically.
   if (isset($this->bindings[$abstract])) {
   return $this->bindings[$abstract]['concrete'];
   }
   return $abstract;
   }
   /**
   * Get the contextual concrete binding for the given abstract.
   *
   * @param string|callable $abstract
   * @return \Closure|string|array|null
   */
   protected function getContextualConcrete($abstract)
   {
   if (! is_null($binding = $this->findInContextualBindings($abstract))) {
   return $binding;
   }
   // Next we need to see if a contextual binding might be bound under an
   alias of the
   // given abstract type. So, we will need to check if any aliases exist
   with this
   // type and then spin through them and check for contextual bindings on
   these.
   if (empty($this->abstractAliases[$abstract])) {
   return;
   }
   foreach ($this->abstractAliases[$abstract] as $alias) {
   if (! is_null($binding = $this->findInContextualBindings($alias))) {
   return $binding;
   }
   }
   }
   /**
   * Find the concrete binding for the given abstract in the contextual
   binding array.
   *
   * @param string|callable $abstract
   * @return \Closure|string|null
   */
   protected function findInContextualBindings($abstract)
   {
   return $this->contextual[end($this->buildStack)][$abstract] ?? null;
   }
   /**
   * Determine if the given concrete is buildable.
   *
   * @param mixed $concrete
   * @param string $abstract
   * @return bool
   */
   protected function isBuildable($concrete, $abstract)
   {
   return $concrete === $abstract || $concrete instanceof Closure;
   }
   /**
   * Instantiate a concrete instance of the given type.
   *
   * @param \Closure|string $concrete
   * @return mixed
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   * @throws \Illuminate\Contracts\Container\CircularDependencyException
   */
   public function build($concrete)
   {
   // If the concrete type is actually a Closure, we will just execute it
   and
   // hand back the results of the functions, which allows functions to be
   // used as resolvers for more fine-tuned resolution of these objects.
   if ($concrete instanceof Closure) {
   return $concrete($this, $this->getLastParameterOverride());
   }
   try {
   $reflector = new ReflectionClass($concrete);
   } catch (ReflectionException $e) {
   throw new BindingResolutionException("Target class [$concrete] does not
   exist.", 0, $e);
   }
   // If the type is not instantiable, the developer is attempting to
   resolve
   // an abstract type such as an Interface or Abstract Class and there is
   // no binding registered for the abstractions so we need to bail out.
   if (! $reflector->isInstantiable()) {
   return $this->notInstantiable($concrete);
   }
   // if (in_array($concrete, $this->buildStack)) {
   // throw new CircularDependencyException("Circular dependency detected
   while resolving [{$concrete}].");
   // }
   $this->buildStack[] = $concrete;
   $constructor = $reflector->getConstructor();
   // If there are no constructors, that means there are no dependencies
   then
   // we can just resolve the instances of the objects right away, without
   // resolving any other types or dependencies out of these containers.
   if (is_null($constructor)) {
   array_pop($this->buildStack);
   return new $concrete;
   }
   $dependencies = $constructor->getParameters();
   // Once we have all the constructor's parameters we can create each of
   the
   // dependency instances and then use the reflection instances to make a
   // new instance of this class, injecting the created dependencies in.
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
   * @param \ReflectionParameter[] $dependencies
   * @return array
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
   protected function resolveDependencies(array $dependencies)
   {
   $results = [];
   foreach ($dependencies as $dependency) {
   // If the dependency has an override for this particular build we will
   use
   // that instead as the value. Otherwise, we will continue with this run
   // of resolutions and let reflection attempt to determine the result.
   if ($this->hasParameterOverride($dependency)) {
   $results[] = $this->getParameterOverride($dependency);
   continue;
   }
   // If the class is null, it means the dependency is a string or some
   other
   // primitive type which we can not resolve since it is not a class and
   // we will just bomb out with an error since we have no-where to go.
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
   * @param \ReflectionParameter $dependency
   * @return bool
   */
   protected function hasParameterOverride($dependency)
   {
   return array_key_exists(
   $dependency->name, $this->getLastParameterOverride()
   );
   }
   /**
   * Get a parameter override for a dependency.
   *
   * @param \ReflectionParameter $dependency
   * @return mixed
   */
   protected function getParameterOverride($dependency)
   {
   return $this->getLastParameterOverride()[$dependency->name];
   }
   /**
   * Get the last parameter override.
   *
   * @return array
   */
   protected function getLastParameterOverride()
   {
   return count($this->with) ? end($this->with) : [];
   }
   /**
   * Resolve a non-class hinted primitive dependency.
   *
   * @param \ReflectionParameter $parameter
   * @return mixed
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
   protected function resolvePrimitive(ReflectionParameter $parameter)
   {
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
   * @param \ReflectionParameter $parameter
   * @return mixed
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
   protected function resolveClass(ReflectionParameter $parameter)
   {
   try {
   return $parameter->isVariadic()
   ? $this->resolveVariadicClass($parameter)
   : $this->make(Util::getParameterClassName($parameter));
   }
   // If we can not resolve the class instance, we will check to see if
   the value
   // is optional, and if it is we will return the optional parameter
   value as
   // the value of the dependency, similarly to how we do this with
   scalars.
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
   * @param \ReflectionParameter $parameter
   * @return mixed
   */
   protected function resolveVariadicClass(ReflectionParameter $parameter)
   {
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
   protected function notInstantiable($concrete)
   {
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
   * @param \ReflectionParameter $parameter
   * @return void
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
   protected function unresolvablePrimitive(ReflectionParameter
   $parameter)
   {
   $message = "Unresolvable dependency resolving [$parameter] in class
   {$parameter->getDeclaringClass()->getName()}";
   throw new BindingResolutionException($message);
   }
   /**
   * Register a new before resolving callback for all types.
   *
   * @param \Closure|string $abstract
   * @param \Closure|null $callback
   * @return void
   */
   public function beforeResolving($abstract, Closure $callback = null)
   {
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
   * @param \Closure|string $abstract
   * @param \Closure|null $callback
   * @return void
   */
   public function resolving($abstract, Closure $callback = null)
   {
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
   * @param \Closure|string $abstract
   * @param \Closure|null $callback
   * @return void
   */
   public function afterResolving($abstract, Closure $callback = null)
   {
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
   protected function fireBeforeResolvingCallbacks($abstract, $parameters
   = [])
   {
   $this->fireBeforeCallbackArray($abstract, $parameters,
   $this->globalBeforeResolvingCallbacks);
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
   protected function fireResolvingCallbacks($abstract, $object)
   {
   $this->fireCallbackArray($object, $this->globalResolvingCallbacks);
   $this->fireCallbackArray(
   $object, $this->getCallbacksForType($abstract, $object,
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
   protected function fireAfterResolvingCallbacks($abstract, $object)
   {
   $this->fireCallbackArray($object,
   $this->globalAfterResolvingCallbacks);
   $this->fireCallbackArray(
   $object, $this->getCallbacksForType($abstract, $object,
   $this->afterResolvingCallbacks)
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
   protected function getCallbacksForType($abstract, $object, array
   $callbacksPerType)
   {
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
   protected function fireCallbackArray($object, array $callbacks)
   {
   foreach ($callbacks as $callback) {
   $callback($object, $this);
   }
   }
   /**
   * Get the container's bindings.
   *
   * @return array
   */
   public function getBindings()
   {
   return $this->bindings;
   }
   /**
   * Get the alias for an abstract if available.
   *
   * @param string $abstract
   * @return string
   */
   public function getAlias($abstract)
   {
   return isset($this->aliases[$abstract])
   ? $this->getAlias($this->aliases[$abstract])
   : $abstract;
   }
   /**
   * Get the extender callbacks for a given type.
   *
   * @param string $abstract
   * @return array
   */
   protected function getExtenders($abstract)
   {
   return $this->extenders[$this->getAlias($abstract)] ?? [];
   }
   /**
   * Remove all of the extender callbacks for a given type.
   *
   * @param string $abstract
   * @return void
   */
   public function forgetExtenders($abstract)
   {
   unset($this->extenders[$this->getAlias($abstract)]);
   }
   /**
   * Drop all of the stale instances and aliases.
   *
   * @param string $abstract
   * @return void
   */
   protected function dropStaleInstances($abstract)
   {
   unset($this->instances[$abstract], $this->aliases[$abstract]);
   }
   /**
   * Remove a resolved instance from the instance cache.
   *
   * @param string $abstract
   * @return void
   */
   public function forgetInstance($abstract)
   {
   unset($this->instances[$abstract]);
   }
   /**
   * Clear all of the instances from the container.
   *
   * @return void
   */
   public function forgetInstances()
   {
   $this->instances = [];
   }
   /**
   * Clear all of the scoped instances from the container.
   *
   * @return void
   */
   public function forgetScopedInstances()
   {
   foreach ($this->scopedInstances as $scoped) {
   unset($this->instances[$scoped]);
   }
   }

} 
