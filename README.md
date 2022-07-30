# Task.

Implement REST API that allows downloading of a dynamically generated resource. Generating the resource takes significant time (5s) to complete and should be cached. Usage of external caching services is not allowed. Local disk usage is allowed. Caching policy is LRU (least recently used), maximum cache size is 10 elements. 

Implement REST API with Symfony or another REST framework of your choice. Implement unit tests with PHPUnit. 

The task is supposed to be completed within 1 - 1.5 hours.

# API methods.

`GET /resource/{id}`

Obtains expensive resource by id and returns it to the caller, optionally caching.

# Tests.

Implement unit tests that check if API is working as expected:
- resource is cached on subsequent requests;
- resources are deleted from cache as per LRU policy; 
