@extends('layouts.app')

@section('title', 'Toutes les notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6 border-b pb-4">
        <h3 class="text-xl font-semibold text-gray-800">Toutes vos notifications</h3>
        {{-- Ici, vous pourriez ajouter un bouton pour marquer tout comme lu si vous ne le faites pas automatiquement --}}
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="p-4 rounded-lg transition-colors {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50 border border-blue-200' }}">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        @if($notification->type === 'App\Notifications\MembershipExpiring')
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-id-card text-blue-600"></i>
                            </div>
                        @elseif($notification->type === 'App\Notifications\ClassBooked')
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-green-600"></i>
                            </div>
                        @elseif($notification->type === 'App\Notifications\ChallengeJoined')
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-trophy text-yellow-600"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-bell text-gray-600"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-4 flex-grow">
                        <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? 'Notification sans message.' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->read_at)
                        <span class="w-2.5 h-2.5 bg-blue-500 rounded-full" title="Non lue"></span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-bell-slash text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">Vous n'avez aucune notification pour le moment.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection