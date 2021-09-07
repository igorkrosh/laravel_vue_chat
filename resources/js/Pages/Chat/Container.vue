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
            messages: []
        }
    },
    methods: {
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
            this.getMessages();
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
    }
})
</script>
