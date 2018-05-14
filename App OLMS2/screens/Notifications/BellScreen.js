import React, { Component } from 'react';
import {TouchableOpacity, AsyncStorage, FlatList, RefreshControl} from 'react-native'
import { Container, Header, Content, Left, Body, Right, Text, Title, CardItem, Card, Button } from 'native-base';
import {Entypo, MaterialCommunityIcons} from '@expo/vector-icons';
import axios from 'axios';
import {HOST} from '../host'
export default class BellScreen extends Component {
    constructor(props){
        super(props);
        this.state = {
            refreshing: false
        }
        this.setUser();
    }

    _onRefresh = () => {
        this.setState({refreshing: true})
        this.getNotifications(this.state.username, this.state.password)
        .then(() => {
            this.setState({refreshing: false})
        })
    }

    setUser = async() => {
        this.setState({
            username: await AsyncStorage.getItem('username'),
            password: await AsyncStorage.getItem('password'),
        });
        this.getNotifications(this.state.username, this.state.password);
    }

    getNotifications = async(username, password) => {
        const subject = await axios({
            method: 'post',
            url: HOST+'mobileNotifications',
            data: {
                username,
                password
            }
        });
        this.setState({data: await subject.data.subjects});
    }

    maskallRead = async(username, password) => {
        const subject = await axios({
            method: 'post',
            url: HOST+'mobilereadallNotifications',
            data: {
                username,
                password,
            }
        });
        this.setState({data: await subject.data.subjects});
    }

    codeSubject = async(id) => {
        const subject = await axios({
            method: 'post',
            url: HOST+'mobilecodeSubject',
            data: {
                username: this.state.username,
                password: this.state.password,
                id,
            }
        });
        this.setState({codeSubject: await subject.data.subjects});
    }
    typeNotification = (item) => {
        this.codeSubject(item.data.target);
        if (item.type == "App\\Notifications\\NewsNotifications") {
            return <TouchableOpacity onPress={ () => this.props.navigation.navigate('SubjectScreen', {name : item.data.data, id: item.data.target, code: this.state.codeSubject})}>
                        <CardItem>
                            <Left>
                                <Text>รายวิชา {item.data.data} มีข้อความใหม่</Text>
                            </Left>
                            <Right>
                                <Entypo style={{fontSize: 30, color: '#2962FF'}} name='chevron-small-right'></Entypo>
                            </Right>
                        </CardItem>
                    </TouchableOpacity>;
        } else {
            return <CardItem>
                        <Left>
                            <Text>รายวิชา {item.data.data} มีคำร้องขอลา</Text>
                        </Left>
                        <Right></Right>
                    </CardItem>
        }
    }
    render() {
        const { navigate } = this.props.navigation;
        return (
            <Container>
                <Header>
                    <Left></Left>
                    <Body>
                        <Title>
                            แจ้งเตือน
                        </Title>
                    </Body>
                    <Right>
                        <Button transparent onPress={ () => this.maskallRead(this.state.username, this.state.password)} >
                            <MaterialCommunityIcons name='read' size={18}/>
                        </Button>
                    </Right>
                </Header>
                <Content
                    refreshControl={
                        <RefreshControl
                            refreshing={this.state.refreshing}
                            onRefresh={this._onRefresh}
                    />}
                > 
                    <FlatList 
                        data={this.state.data}
                        renderItem={({item}) => 
                            <Card style={{flex: 0}} >
                                {this.typeNotification(item)}
                            </Card>
                        }
                        keyExtractor={(item, index) => index}
                    />
                </Content>
            </Container>
        );
    }
}

