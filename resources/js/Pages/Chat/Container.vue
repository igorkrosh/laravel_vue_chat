<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <ChatRoomSelection
                    v-if="currentRoom.id"
                    :rooms="chatRooms"
                    :currentRoom="currentRoom"
                    v-on:roomchacged="setRoom($event)"
                />
            </h2>
        </template>
        <ul>
            <li v-for="(user, index) in users" :key="index">{{user.name}}</li>
        </ul>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <MessageContainer :messages="messages"/>
                    <InputMessage :room="currentRoom" v-on:messageSend="getMessages()"/>
                </div>
            </div>
        </div>
        
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import MessageContainer from './MessageContainer.vue'
import InputMessage from './InputMessage.vue'
import ChatRoomSelection from './ChatRoomSelection.vue'

export default defineComponent({
    components: {
        AppLayout,
        MessageContainer,
        InputMessage,
        ChatRoomSelection
    },
    data() {
        return {
            chatRooms: [],
            currentRoom: [],
            messages: [],
            users: null
        }
    },
    watch: {
        currentRoom(newValue, oldValue) {
            if (oldValue.id)
            {
                this.disconnect(oldValue);
            }
            this.connect();
        }
    },
    methods: {
        connect() {
            if (this.currentRoom.id)
            {
                let vm = this;
                this.getMessages();
                window.Echo.private(`chat.${this.currentRoom.id}`)
                .listen('NewChatMessage', e => {
                    vm.getMessages();
                })
            }
        },
        disconnect(room) {
            window.Echo.leave(`chat.${room.id}`)
        },
        getRooms() {
            axios.get('/chat/rooms')
                .then(response => {
                    this.chatRooms = response.data;
                    this.setRoom(response.data[0]);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        setRoom(room) {
            this.currentRoom = room;
            
        },
        getMessages() {
            axios.get(`/chat/room/${this.currentRoom.id}/messages `)
                .then(responce => {
                    this.messages = responce.data;
                })
                .catch(error => {
                    console.log(error);
                })
        }
    },
    created() {
        this.getRooms();
    },
    mounted() {
        /*
        Echo.join('users')
        .here((users) => {
            this.users = users
            console.log(users)
        })
        .joining((user) => {
            this.users.push(user)
        })
        .leaving((user) => {
            this.users.splice(this.users.indexOf(user), 1)
        })
        */
       /*
       Echo.channel('users')
        .listen('ChatMessageWasReceived', (e) => {
            console.log(e.user, e.chatMessage);
        });
        */
    }
})
</script>
