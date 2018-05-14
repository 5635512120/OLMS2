import React, { Component } from 'react';
import {  AsyncStorage, StyleSheet, Dimensions, FlatList, ListView, RefreshControl,Platform } from 'react-native';
import { Container, Header, Content, Button, Icon, List, ListItem, Text, Body, Title, Right, Left, View, Toast, Grid, Col, SwipeRow } from 'native-base';
import { Ionicons, Feather } from '@expo/vector-icons';
import axios from 'axios';
import {HOST} from '../host';
import moment from 'moment';
import {Entypo, FontAwesome} from '@expo/vector-icons'

export default class Liststatus extends Component {
    constructor(props) {
        super(props);
        this.ds = new ListView.DataSource({ rowHasChanged: (r1, r2) => r1 !== r2 });
        this.state = {
            basic: true,
            check: false,
            listViewData: '',
            refreshing: false,
            showToast: false,
        };
        this.setUser();
    }

    _onRefresh = () => {
        this.setState({refreshing: true});
        this.setState({listViewData: ''});
        this.showStatus(this.state.username, this.state.password)
          .then(() => {
            this.setState({refreshing: false})
          });
    }

    setUser = async() => {
        this.setState({
            username: await AsyncStorage.getItem('username'),
            password: await AsyncStorage.getItem('password'),
            role: await AsyncStorage.getItem('role')
        });
        if(this.state.role == 'teacher'){
            this.setState({
                isTeacher: await true
            });
        } else if (this.state.role == 'student') {
            this.setState({
                isTeacher: await false
            });
        }
        this.showStatus(this.state.username, this.state.password);
    }

    ejectRow = async(username, password, id) => {
        const result = await axios({
            method: 'post',
            url: HOST+'teacherEject',
            data: {
                username, 
                password,
                id
            }
        });
        Toast.show({
            text: "ปฏิเสธแล้ว",
            buttonText: "ตกลง",
            type: "danger"
        });
        this.setState({listViewData: await result.data.subjects});
    }

    acceptRow = async(username, password, id) => {
        const result = await axios({
            method: 'post',
            url: HOST+'teacherAccept',
            data: {
                username, 
                password,
                id
            }
        });
        Toast.show({
            text: "ยอมรับแล้ว",
            buttonText: "ตกลง",
            type: "success"
        });
        this.setState({listViewData: await result.data.subjects});
    }

    showStatus = async(username, password) => {
        if (this.state.role == 'teacher') {
            const result = await axios({
                method: 'post',
                url: HOST+'teacherRequest',
                data: {
                    username,
                    password
                }
            });
            this.setState({listViewData: await result.data.subjects});
        } else if (this.state.role == 'student'){
            const result = await axios({
                method: 'post',
                url: HOST+'mobilestatusActivitys',
                data: {
                    username,
                    password
                }
            });
            this.setState({listViewData: await result.data.subjects});
        }
    }

    status = (status) => {
        if(status == 1){
            return (<Entypo name='check' style={{color: 'green'}} />);
        } else if (status == 3) {
            return (<Entypo name='cross' style={{color: 'red'}} />);
        }
        return (<Entypo name='stopwatch' style={{color: 'blue'}} />);
    }
    
    deleteRow = async(username, password, id) => {
        const result = await axios({
            method: 'post',
            url: HOST+'mobileDelete',
            data: {
                username, 
                password,
                id
            }
        });
        Toast.show({
            text: "ลบคำร้องขอแล้ว",
            buttonText: "ตกลง",
            type: "success"
        });
        this.setState({listViewData: await result.data.subjects});
    }

    render() {
    const ds = new ListView.DataSource({ rowHasChanged: (r1, r2) => r1 !== r2 });
    const role = this.state.isTeacher 
                                    ?   <FlatList
                                            data={this.state.listViewData}
                                            renderItem={({ item }) => <SwipeRow
                                                    rightOpenValue={-150}
                                                    body={
                                                        <Body>
                                                            <Text>{item.nameSubject} <Text note>({item.code})</Text> {this.status(item.status)}</Text>
                                                            <Text note>{item.formats} เนื่องจาก {item.description}</Text>
                                                            <Text note><Text>ภายในวันที่</Text> {moment(item.Start).format('l')}</Text>
                                                        </Body>
                                                    }
                                                    right={
                                                        <Grid>
                                                            <Col>
                                                            <Button full danger onPress={_ => this.ejectRow(this.state.username, this.state.password ,item.id)}>
                                                                <FontAwesome style={{color: 'white'}} size={20} name="times-circle" />
                                                            </Button>
                                                            </Col>
                                                            <Col>
                                                            <Button success onPress={_ => this.acceptRow(this.state.username, this.state.password ,item.id)}>
                                                                <FontAwesome style={{color: 'white'}} size={20} name="check-circle" />
                                                            </Button>
                                                            </Col>
                                                        </Grid>
                                                    }
                                                />
                                            }
                                            keyExtractor={(item, index) => index}
                                        />
                                    :   <FlatList      
                                            data={this.state.listViewData}
                                            renderItem={({ item }) => <SwipeRow
                                                    rightOpenValue={!item.status ? -75 : 0}
                                                    body={
                                                            <Body>
                                                                <Text>{item.nameSubject} <Text note>({item.code})</Text> {this.status(item.status)}</Text>
                                                                <Text note>{item.formats} เนื่องจาก {item.description}</Text>
                                                                <Text note><Text>ภายในวันที่</Text> {moment(item.Start).format('l')}</Text>
                                                            </Body>
                                                    }
                                                    right={
                                                            <Button full danger onPress={_ => this.deleteRow(this.state.username, this.state.password ,item.id)}>
                                                                <Icon active name="trash" />
                                                            </Button>
                                                    }
                                                />
                                            }
                                            keyExtractor={(item, index) => index}
                                        />
    return (
      <Container>
            <Header>
            <Body>
                <Title>
                    สถานะ
                </Title>
            </Body>
            </Header>
            <Content refreshControl={
                <RefreshControl
                  refreshing={this.state.refreshing}
                  onRefresh={this._onRefresh}
                />}>
                {role}
            </Content>
        </Container>
    );
  }
}

const { width, height } = Dimensions.get("window");
const styles = StyleSheet.create({
    list: {
        paddingLeft: 15,
    },
    width: {
        width: width/2,
    }
});