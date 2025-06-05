<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - Clinic Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-10">
            <h1 class="text-3xl font-bold text-indigo-700">Clinic Booking System API Documentation</h1>
            <p class="text-gray-600 mt-2">Complete guide to available API endpoints and their usage</p>
        </header>

        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">API Base URL</h2>
            <div class="bg-gray-800 text-green-400 p-3 rounded font-mono">{{ url('/api/v1') }}</div>
            <p class="mt-2 text-gray-600">All endpoints should be prefixed with this base URL.</p>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Authentication</h2>
            <div class="bg-white rounded-lg shadow-md p-6">
                <p class="mb-2">This API uses Laravel Sanctum for authentication. Include the following header with your API requests:</p>
                <div class="bg-gray-100 p-2 rounded font-mono text-sm">
                    Authorization: Bearer {your_token}
                </div>
                <p class="mt-4 text-gray-600">To obtain a token, use the login endpoint described below.</p>
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Available Endpoints</h2>

            @foreach($routes as $route)
                <div class="bg-white rounded-lg shadow-md p-6 mb-4">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        @foreach($route['methods'] as $method)
                            @if($method == 'GET')
                                <span class="px-2 py-1 bg-green-500 text-white text-xs font-bold rounded">{{ $method }}</span>
                            @elseif($method == 'POST')
                                <span class="px-2 py-1 bg-blue-500 text-white text-xs font-bold rounded">{{ $method }}</span>
                            @elseif($method == 'PUT' || $method == 'PATCH')
                                <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded">{{ $method }}</span>
                            @elseif($method == 'DELETE')
                                <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded">{{ $method }}</span>
                            @else
                                <span class="px-2 py-1 bg-gray-500 text-white text-xs font-bold rounded">{{ $method }}</span>
                            @endif
                        @endforeach
                        <span class="font-mono text-lg">{{ $route['uri'] }}</span>
                    </div>
                    
                   
                    
                    <div class="mt-4">
                        <div class="text-sm text-gray-500">Controller: <span class="font-mono">{{ class_basename($route['controller']) }}</span></div>
                        <div class="text-sm text-gray-500">Method: <span class="font-mono">{{ $route['method'] }}</span></div>
                    </div>
                    
                    @if(count($route['parameters']) > 0)
                        <div class="mt-4">
                            <h4 class="font-medium text-gray-700">Parameters</h4>
                            <div class="mt-2 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                            <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th class="px-4 py-2 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($route['parameters'] as $param)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap font-mono">{{ $param['name'] }}</td>
                                                <td class="px-4 py-2 whitespace-nowrap">{{ $param['type'] }}</td>
                                                <td class="px-4 py-2">{{ $param['description'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    
                    @if(count($route['responses']) > 0)
                        <div class="mt-4">
                            <h4 class="font-medium text-gray-700">Response Codes</h4>
                            <div class="mt-2">
                                <ul class="space-y-1">
                                    @foreach($route['responses'] as $response)
                                        <li>
                                            <span class="font-mono">{{ $response['code'] }}</span> - {{ $response['description'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
