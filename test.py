@csrf_exempt
def uploadify_script(request,user_id):
    user = request.user
    response=HttpResponse()
    ret="0"        
    file = request.FILES.get("Filedata",None)
    rooms=request.REQUEST.get('room1','')
    multi_cabinet=request.REQUEST.get('multi_cabinet','')
    cabinet_list=multi_cabinet.split('|')
    excelDevices=[]
    for a in cabinet_list:
        if a!="":
            excelDevices.append(Cabinet.objects.get(id=a).name)
    if file: 
        res = _upload(file)       
        if res[0]:
            ret=res[1]
        else:
            ret=""
    print res[1]
    filedir=settings.CURRENT_PATH+"/apps/devices/media/excel/"+res[1]
    try:
        data=xlrd.open_workbook(filedir)
    except:
        return HttpResponse('excel的版本太低不能导入,请转换为更高版本的excel')
    os.remove(filedir)
    importRow=[]
    add_ipUeds=[]
    add_devices=[]
    if rooms!=""  and multi_cabinet!="":
        table = data.sheets()[0]
        nrows=table.nrows
        # 数据从第二行开始读
        for row in range(1,nrows):
            room_value=table.row(row)[1].value
            cabinet_value=table.row(row)[2].value
            if Room.objects.get(id=rooms).name==room_value and cabinet_value in excelDevices:
                importRow.append(row)
        if len(importRow)<5000:
            devicesAndUs={}
            for i in importRow:
                if table.row(i)[3].value=="":
                    return HttpResponse('第'+str(int(i)+1)+'行设备型号不能为空')
                if table.row(i)[4].value=="":
                    return HttpResponse('第'+str(int(i)+1)+'行设备尺寸不能为空')
                if table.row(i)[5].value=="":
                    return HttpResponse('第'+str(int(i)+1)+'行机柜U位不能为空')
                if table.row(i)[11].value=="":
                    return HttpResponse('第'+str(int(i)+1)+'行设备类型不能为空')
                if table.row(i)[12].value=="":
                    return HttpResponse('第'+str(int(i)+1)+'行设备品牌不能为空')
                if table.row(i)[13].value=="":
                    return HttpResponse('第'+str(int(i)+1)+'行设备所属公司不能为空')
                try:
                    company_id=Info.objects.get(name=table.row(i)[13].value.strip()).id
                except:
                    return HttpResponse('第'+str(int(i)+1)+'行该公司信息不存在')
                try:
                    cabinet_id=Cabinet.objects.get(name=table.row(i)[2].value.strip(),room_id=rooms).id
                except:
                    return HttpResponse('第'+str(int(i)+1)+'机柜名称重复')
                # 一个设备在合同中如果没有选择机柜和U位信息不能添加
                device_deal=DealCabinet.objects.filter(deal__company__id=company_id,room_id=rooms,cabinets=cabinet_id)

                if device_deal.count()==0:
                    return HttpResponse('第'+str(int(i)+1)+'行的信息不在合同内,不能导入')
                #判断U位是否重复和是否在合同内
                position=str(table.row(i)[5].value).strip()
                device_deal_count=DealCabinet.objects.filter(deal__company__id=company_id,room_id=rooms,cabinets=cabinet_id,positions__contains=position[:-1]).count()
                if device_deal_count==0:
                    return HttpResponse('第'+str(int(i)+1)+'行机柜U位不在合同内')
                dev_count = Device.objects.filter(company__id = company_id,room__id=rooms,cabinets__id = cabinet_id,position=position[:-1]).count()
                if dev_count > 0:
                    return HttpResponse('第'+str(int(i)+1)+'行机柜U位已存在')
                cabinet_key=str(table.row(i)[2].value).strip()
                position_value=str(table.row(i)[5].value).strip()
                if cabinet_key not in devicesAndUs:
                    devicesAndUs[cabinet_key]=[position_value]
                else:
                    if position_value in devicesAndUs[cabinet_key]:
                        return HttpResponse('第'+str(int(i)+1)+'行该设备的U位重复！')
                    else:
                        devicesAndUs[cabinet_key].append(position_value)
                allIPArea=IPArea.objects.filter(room_id=rooms)
                wide_ip=table.row(i)[0].value.strip()
                if wide_ip!="":
                    current_id=0
                    for ipa_s in allIPArea:
                        if ipa_s.from_int<=ip_to_int(wide_ip)<=ipa_s.to_int:
                            current_id=ipa_s.id
                    if current_id==0:
                        return HttpResponse('第'+str(int(i)+1)+'行IP没有分配在该机房内,不能添加!')

                    out_ip=ip_to_int(wide_ip)
                    if user.is_superuser:
                        ipused = IPUed.objects.filter(from_int=out_ip)
                        dealips = DealIP.objects.filter(dealips__deal__company__id = company_id,from_int__lte=out_ip,to_int__gte=out_ip)
                    else:
                        user_pro = User_Profile.objects.get(user=user)
                        if user_pro.company.id == 1:
                            ipused = IPUedTest.objects.filter(from_int=out_ip)
                            dealips = DealIPTest.objects.filter(dealips__deal__company__id =company_id,from_int__lte=out_ip,to_int__gte=out_ip)
                        else:
                            ipused = IPUed.objects.filter(from_int=out_ip)
                            dealips = DealIP.objects.filter(dealips__deal__company__id = company_id,from_int__lte=out_ip,to_int__gte=out_ip)
                    if len(ipused)>0:
                        return HttpResponse('第'+str(int(i)+1)+'行IP已被占用!')
                    if len(dealips) == 0:
                        return HttpResponse('第'+str(int(i)+1)+'行IP不属于该公司')
                    if user.is_superuser:
                        ip_ued=IPUed()
                        ip_ued.company_id=company_id
                    else:
                        user_pro = User_Profile.objects.get(user=user)
                        if user_pro.company.id == 1:
                            ip_ued=IPUedTest()
                            ip_ued.company_id=1
                        else:
                            ip_ued=IPUed()
                            ip_ued.company_id=user_pro.company.id
          
                    ip_ued.iparea_id=current_id
                    ip_ued.ip_from=wide_ip
                    ip_ued.ip_to=wide_ip
                    ip_ued.from_int=out_ip
                    ip_ued.to_int=out_ip
                    add_ipUeds.append(ip_ued)
                    add_ipUeds.append(ip_ued)

                if user.is_superuser:
                    device=Device()
                    device.company_id=company_id
                else:
                    user_pro = User_Profile.objects.get(user = user)
                    if user_pro.company.id == 1:
                        device=DeviceTest()
                        device.company_id=company_id
                    else:
                        device=Device()
                        device.company_id=user_pro.company.id
                #设备状态设置固定为1，bug
                device.status=1
                device.maskcode=""
                device.ip=wide_ip
                device.room_id=rooms
                device.cabinets_id=cabinet_id
                try:
                    version_id=Version.objects.get(name=table.row(i)[3].value.strip()).id
                    device.version_id=version_id
                except:
                    version=Version()
                    version.name=table.row(i)[3].value
                    version.save()
                    device.version_id=version.id
                size=str(table.row(i)[4].value).strip()
                if size[0:-1].isdigit():
                    if 0<int(size[0:-1])<7:
                        device.size=int(size[0:-1])
                    else:
                        return HttpResponse('第'+str(int(i)+1)+'行设备尺寸只能在1U到6U之间')
                else:
                    return HttpResponse('第'+str(int(i)+1)+'行设备尺寸必须为整型+U的格式')
                device.position=int(position[0:-1])
                device.label=table.row(i)[6].value
                device.ip_inside=table.row(i)[7].value
                device.gateway=table.row(i)[8].value
                device.hardware_sn=table.row(i)[9].value
                device.note=table.row(i)[10].value
                device_dtype=table.row(i)[11].value.strip()
                if device_dtype==u"交换机":
                    device.dtype=0;
                elif device_dtype==u'服务器':
                    device.dtype=1
                elif device_dtype==u'路由器':
                    device.dtype=2
                elif device_dtype==u'防火墙':
                    device.dtype=3
                elif device_dtype==u'存储设备':
                    device.dtype=4
                elif device_dtype==u'负载均衡设备':
                    device.dtype=5
                elif device_dtype==u'组装服务器':
                    device.dtype=6
                else:
                    return HttpResponse('第'+str(int(i)+1)+'行设备类型必须为:交换机,服务器,路由器,防火墙,存储设备,负载均衡设备,组装服务器。中的一种!')
                try:
                    brand_id=Brand.objects.get(name=table.row(i)[12].value.strip()).id
                    device.brand_id=brand_id
                except:
                    brand=Brand()
                    brand.name=table.row(i)[12].value
                    brand.save()
                    device.brand_id=brand.id
                add_devices.append(device)
        else:
            return HttpResponse('数据量太大,不能导入')

        if len(importRow)==0:
            return HttpResponse('没有符合条件的数据导入')   
        if len(add_devices)==len(importRow):
            for i in add_devices:
                try:
                    i.save()
                except:
                    return HttpResponse('设备的信息有误，不能添加')
            for i in add_ipUeds:
                try:
                    i.save()
                except:
                    return HttpResponse('设备的信息有误，不能添加')
            return HttpResponse('已成功添加'+str(len(importRow))+'条数据')       
    return response