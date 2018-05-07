<template>
    <div>
        <h2>Players</h2>
        <hr>
        <div class="players-container">
            <div class="player-card">
                <div class="player-card__content">
                    <input class="player-card__input" type="text" v-model="newPlayer.first_name" placeholder="John..">
                    <input class="player-card__input" type="text" v-model="newPlayer.last_name" placeholder="Doe..">
                    <div class="player-card__select-content">
                        <label>Select a Team:</label>
                        <select class="player-card__select" v-on:change="changeTeam" v-model="selectedTeam">
                                <option disabled value=''>
                                    Free Agent
                                </option>
                                <option v-for="team in teams" v-bind:key="team.id" v-bind:value="team">
                                    {{ team.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="player-card__buttons">
                    <button class="player-card__button" type="submit" v-on:click="createPlayer($event)">CREATE</button>
                </div>
            </div>
            <div class="player-card" v-for="player in players" :key="player.id">
                <div class="player-card__content">
                    <input class="player-card__input" type="text" v-model="player.first_name">
                    <input class="player-card__input" type="text" v-model="player.last_name">
                    <div class="player-card__select-content">
                        <label>Select a Team:</label>
                        <select class="player-card__select" v-model="player.team">
                            <option :value="null">
                                Free Agent
                            </option>
                            <option v-for="team in teams" v-bind:key="team.id" v-bind:value="team" :selected="player.team && player.team.id == team.id">
                                {{ team.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="player-card__buttons">
                    <button class="player-card__button" v-on:click="updatePlayer($event, player)">UPDATE</button>
                    <button class="player-card__button-red" v-on:click="deletePlayer($event, player.id)">DELETE</button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                players: [],
                teams: [],
                newPlayer: {
                    first_name: '',
                    last_name: '',
                    team: null,
                },
                selectedTeam: '',
            }
        },
        created() {
            this.fetchPlayers();
            this.fetchTeams();
        },
        methods: {
            fetchPlayers: function() {
                fetch('/api/players')
                .then(response => response.json())
                .then(response => {
                    this.players = response.data;
                });
            },
            fetchTeams: function() {
                fetch('/api/teams')
                .then(response => response.json())
                .then(response => {
                    this.teams = response.data;
                });
            },
            createPlayer(event) {
                event.preventDefault();
                fetch('/api/players', {
                    method: "POST",
                    body: JSON.stringify(this.newPlayer),
                    headers: {
                        'content-type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(response => {
                    this.newPlayer.first_name = '';
                    this.newPlayer.last_name = '';
                    this.newPlayer.team = null;
                    this.selectedTeam = '';
                    alert("Player Created");
                    this.fetchPlayers();
                    this.fetchTeams();
                })
                .catch(err => console.log(err));
            },
            updatePlayer(event, player) {
                event.preventDefault();
                fetch('/api/players/' + player.id, {
                    method: "PUT",
                    body: JSON.stringify(player),
                    headers: {
                        'content-type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(response => {
                    this.newPlayer.first_name = '';
                    this.newPlayer.last_name = '';
                    this.newPlayer.team = null;
                    this.selectedTeam = '';
                    alert("Player Updated");
                    this.fetchPlayers();
                    this.fetchTeams();
                })
                .catch(err => console.log(err));
            },
            deletePlayer(event, id) {
                event.preventDefault();
                if(confirm("Are you sure?")) {

                    fetch('/api/players/' + id, {
                    method: "DELETE",
                    })
                    .then(response => response.json())
                    .then(response => {
                        this.newPlayer.first_name = '';
                        this.newPlayer.last_name = '';
                        this.newPlayer.team = null;
                        this.selectedTeam = '';
                        alert("Player Removed.");
                        this.fetchPlayers();
                        this.fetchTeams();
                    })
                    .catch(err => console.log(err));
                }
            },
            changeTeam(event, player = null) {
                if(player) {
                    if(this.selectedTeam == '') {
                        player.team = null;
                    }
                    else {
                        player.team = this.selectedTeam;
                    }
                }
                else {
                    console.log(this.selectedTeam);
                    if(this.selectedTeam == '') {
                        this.newPlayer.team = null;
                    }
                    else {
                        this.newPlayer.team = this.selectedTeam;
                    }
                    console.log(this.newPlayer.team);
                }
            }
        }
    }
</script>